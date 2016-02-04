<?php

class EvsMiddleware {

	private $req = null;
	private $res = null;

	private $contentTypes = array(
        'json'   =>  array(
            'function'  =>  'parseJson',
            'header'    =>  'application/json'
        ),
        'jsonp' =>  array(
            'header'    =>  'application/javascript'
        ),
        'xml'   =>  array(
            'function'  =>  'parseXml',
            'header'    =>  'text/xml'
        )
    );

	private function isSecure() {
		return !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']==='https';
	}

	private function isValidFormat($req) {
		// Default to json
	    $f = ($req->getAttribute('format')) ? $req->getAttribute('format') : 'json';
	    // If in array and has a callable function
		return isset($this->contentTypes[$f]) && is_callable($this->contentTypes[$f]['function']);
	}

	private function parse($data) {
		// Default to json
	    $f = ($this->req->getAttribute('format')) ? $this->req->getAttribute('format') : 'json';
	    // Check for callback
	    $callback = ($this->req->getQueryParams()['callback'] && is_string($this->req->getQueryParams()['callback'])) ? $this->req->getQueryParams()['callback'] : false;

	    if ($this->isValidFormat($this->req)) {
	        $result = call_user_func($this->contentTypes[$f]['function'], $data);

	        // JSONP result
	        if($callback && $f == 'json') {
	            $f = 'jsonp';
	            $result = " " . $callback . "(" . $result . ") ";
	        }

	        // Process and write in the body
	        if ($result) {
	            $this->res->getBody()->write($result);
	            return $this->res->withHeader('Content-type', $this->contentTypes[$f]['header']);
	        }
	    }

	    return sendError(415);
	}


	private function parseJson($input) {
	    if (function_exists('json_encode')) {
	        $result = json_encode($input, JSON_NUMERIC_CHECK);
	        if ($result) {
	            return $result;
	        }
	    }
	}

	private function parseXml($input) {
	    if (class_exists('SimpleXMLElement')) {
	        try {
	            $xml = new SimpleXMLElement('<root/>');
	            array_walk_recursive($input, array($xml, 'addChild'));
	            return $xml->asXML();

	        } catch (\Exception $e) {
	            // Do nothing
	        }
	    }

	    return $input;
	}

	private function parseCsv($input) {
	    $temp = fopen('php://memory', 'rw');
	    fwrite($temp, $input);
	    fseek($temp, 0);
	    $res = array();
	    while (($data = fgetcsv($temp)) !== false) {
	        $res[] = $data;
	    }
	    fclose($temp);

	    return $res;
	}

	private function getErrorMessage($code) {
		$error_list = array(
			200 =>	"OK",
			201 =>	"Created",
			204 =>	"No Content",
			304 =>	"Not Modified",
			400 =>	"Bad Request",
			401 =>	"Unauthorized",
			403 =>	"Forbidden",
			404 =>	"Not Found",
			405 =>	"Method Not Allowed",
			410 =>	"Gone",
			415 =>	"Unsupported Media Type. Please use JSON or XML.",
			422 =>	"Unprocessable Entity",
			429 =>	"Too Many Requests"
		);

		return isset($error_list[$code]) ? $error_list[$code] : 'Something odd happened. Please contact the administrator.';
	}

	private function sendError($code, $message = null) {

		if(!$this->isValidFormat($this->req)) $this->req->getAttribute('format') = 'json';
		$this->res = $this->res->withStatus($code);

		$data = array(
			'stat' 		=>	'error',
			'message'	=>	($message ? $message : $this->getErrorMessage($code));
		);

		return $this->parse($data);
	} 


    public function __invoke($req, $res, $next) {
    	$this->req = $req;
    	$this->res = $res;

    	// Before processing the request
    	if(!$this->isSecure()) return $this->sendError(403, "Please use HTTPS protocol");
 
 		// Make the request
        $data = $next($req, $res);

        //After processing the request
        return $this->parse($data);
    }
}


