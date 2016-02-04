<?php

class EvsMiddleware {

	private $req;
	private $res;
	private $format;

	private $contentTypes;

	// Returns true when in HTTPS
	private function isSecure() {
		return !empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']==='https';
	}

	// Returns true when format (aka extension) is valid
	private function isValidFormat() {
	    // If in array and has a callable function
		return isset($this->contentTypes[$this->format]) && method_exists($this, $this->contentTypes[$this->format]['function']);
	}

	// JSON encode
	private function parseJson($input) {
	    if (function_exists('json_encode')) {
	        $result = json_encode($input, JSON_NUMERIC_CHECK);
	        if ($result) {
	            return $result;
	        }
	    }
	}

	// Encode into XML
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

	// Encode into CSV
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

	// Grabs the content from the response in JSON, unencodes it to data and returns right format
	private function parse() {
	    // Check for callback
	    $callback = ($this->req->getQueryParams()['callback'] && is_string($this->req->getQueryParams()['callback'])) ? $this->req->getQueryParams()['callback'] : false;

	    $data = json_decode($this->res->getBody());

	    if ($this->isValidFormat()) {

	        $result = call_user_func(array($this, $this->contentTypes[$this->format]['function']), $data);

	        // JSONP result
	        if($callback && $this->format == 'json') {
	            $this->format = 'jsonp';
	            $result = " " . $callback . "(" . $result . ") ";
	        }

	        // Process and write in the body
	        if ($result) {
	        	$this->res->getBody()->rewind();
	        	$this->res->getBody()->write($result);
	            return $this->res->withHeader('Content-type', $this->contentTypes[$this->format]['header']);
	        }
	    }

	    return $this->sendError(415);
	}

	// Default error messages for given codes
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

	// Sends an error 
	private function sendError($code, $message = null) {

		//if(!$this->isValidFormat()) $this->req->getAttribute('format') = 'json';

		$data = array(
			'stat' 		=>	'error',
			'message'	=>	($message ? $message : $this->getErrorMessage($code))
		);

		$this->res = $this->res->write(json_encode($data))->withStatus($code);

		return $this->parse($new_res);
	} 


    public function __invoke($req, $res, $next) {
    	$this->req = $req;
    	$this->res = $res;
    	$this->format = $req->getAttribute('routeInfo')[2]['format'] ? $req->getAttribute('routeInfo')[2]['format'] : 'json';
    	$this->contentTypes = array(
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


    	// Before processing the request
    	if(!$this->isSecure()) return $this->sendError(403, "Please use HTTPS protocol");

 
 		// Make the request
        $this->res = $next($this->req, $this->res);


        //After processing the request


        return $this->parse();
    }
}


