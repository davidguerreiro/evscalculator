<?php
require 'lib/functions.php';


// Generic
$app->get('/', 'goHome');


// Hordes
$app->get('/v1/hello/{name}', function ($request, $response) {
    $name = $request->getAttribute('name');
    $response->getBody()->write("Hello, $name");

    return $response;
});