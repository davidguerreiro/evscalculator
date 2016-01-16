<?php
require 'lib/functions.php';



// Generic
$app->get('/', function($req, $res){
    return $res->withStatus(301)->withHeader('Location', 'https://evscalculator.com');
});


// Hordes
$app->get('/v1/hordes', function ($req, $res) {

    $res->getBody()->write(json_encode(
        getHordes($req->getQueryParams()['stat'], $req->getQueryParams()['game'])
    ));

    return $res->withHeader('Content-type', 'application/json');
});