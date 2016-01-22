<?php



function parse($req, $res, $data) {
    // Default to json
    $f = ($req->getAttribute('format')) ? $req->getAttribute('format') : 'json';
    // Check for callback
    $callback = ($req->getQueryParams('callback') && is_string($req->getQueryParams('callback'))) ? $req->getQueryParams('callback') : false;

    // Supported
    $contentTypes = array(
        'json'   =>  array(
            'function'  =>  'parseJson',
            'header'    =>  'application/json'
        ),
        'xml'   =>  array(
            'function'  =>  'parseXml',
            'header'    =>  'text/xml'
        )
    );

    if (isset($contentTypes[$f]) && is_callable($contentTypes[$f]['function'])) {
        $result = call_user_func($contentTypes[$f]['function'], $data);

        if($f === 'json' && $callback) {
            $result = $callback + "(" + $result + ")";
        }

        if ($result) {
            $res->getBody()->write($result);
            return $res->withHeader('Content-type', $contentTypes[$f]['header']);
        }
    }

    return $data;
}



function parseJson($input) {
    if (function_exists('json_encode')) {
        $result = json_encode($input, JSON_NUMERIC_CHECK);
        if ($result) {
            return $result;
        }
    }
}



function parseXml($input) {
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



function parseCsv($input) {
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

