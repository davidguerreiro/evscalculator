<?php

// This file handles all the API requests

require_once 'lib/API.class.php';
class MyAPI extends API
{

    public function __construct($request, $origin) {
        parent::__construct($request);

        // We need some authentication step here
    }

    /**
     * Example of an Endpoint for Hordes
     */
     protected function hordes() {
        if ($this->method == 'GET') {
        	var_dump($this->request);
        	echo "\nFunction called: ". $this->endpoint."\n";
        	if($this->verb) echo "Option (optional): ". $this->verb."\n";
        	if($this->args) {
        		var_dump($this->args);
        	}
            return "Return hordes here";
        } else {
            return "Only accepts GET requests";
        }
     }
 }


 // Requests from the same server don't have a HTTP_ORIGIN header
if (!array_key_exists('HTTP_ORIGIN', $_SERVER)) {
    $_SERVER['HTTP_ORIGIN'] = $_SERVER['SERVER_NAME'];
}

try {
    $API = new MyAPI($_REQUEST['request'], $_SERVER['HTTP_ORIGIN']);
    echo $API->processAPI();
} catch (Exception $e) {
    echo json_encode(Array('error' => "WTF"));
}