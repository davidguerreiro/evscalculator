<?php
require 'lib/functions.php';



// Generic
$app->get('/', function($req, $res){
    return $res->withStatus(301)->withHeader('Location', 'https://evscalculator.com');
});



// Hordes
$app->get('/v1/hordes', function ($req, $res) {

    $data = getHordes($req->getQueryParams()['stat'], $req->getQueryParams()['game']);

    $res->getBody()->write(json_encode($data));
    return $res->withHeader('Content-type', 'application/json');
});


// Create training
$app->post('/v1/trainings', function ($req, $res) {

    var_dump($req->getParsedBody());

    $data = 'Hola David!';

    $res->getBody()->write($data);
    return $res;
});