<?php

class HttpsMiddleware
{
    public function __invoke($req, $res, $next)
    {

    	if (!empty($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO']!=='https') {
		 	$data = array(
		        'stat'      =>  'error',
		        'message'  =>  'Please use HTTPS protocol.'
		    );
		 	return parse($req, $res, $data);
		}
 
        $res = $next($req, $res);

        return $res;
    }
}