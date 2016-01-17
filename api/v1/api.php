<?php
require 'lib/parser.php';
require 'lib/functions.php';



// Generic
$app->get('/', function($req, $res){
    return $res->withStatus(301)->withHeader('Location', 'https://evscalculator.com');
});


// GET hordes
$app->get('/v1/hordes[.{format}]', function($req, $res) {

    $data = getHordes($req->getQueryParams()['stat'], $req->getQueryParams()['game']);
    
    return parse($req, $res, $data);
});


// GET trainings
$app->get('/v1/trainings[.{format}]', function($req, $res) {

    include_once('lib/key.php');

    $data = $db->select('training', '*');

    return parse($req, $res, $data);
});



// POST trainings
$app->post('/v1/trainings[.{format}]', function($req, $res) {
    // Data received
    $vars = $req->getParsedBody();
    // Data to be inserted
    $insert = array();

   // Requires one of  this to be positive
    $required = array(
        'hp', 
        'attack', 
        'defense', 
        'spattack', 
        'spdefense', 
        'speed'
    );

    // Optional parameters
    $optional = array(
        'id_user', 
        'game', 
        'pokerus', 
        'power_brace', 
        'sturdy_object', 
        'timestamp'
    );

    // Go through required
    foreach($required as $i) {
        // Required must be positive
        if($vars[$i] > 0 && $vars[$i] < 252) {
            $insert[$i] = $vars[$i];
        }
    }

    // If required failed, error
    if(!count($insert)) {
        $res->getBody()->write(json_encode(array("Error" => "Requires at least one stat to be positive")));
        return $res->withHeader('Content-type', 'application/json');
    }

    // Go through optional
    foreach($optional as $i) {
        if(!empty($vars[$i])) $insert[$i] = $vars[$i];
    }

    include_once('lib/key.php');
    $training_id = $db->insert('training', $insert);

    $data = array(
        'stat'      =>  'success',
        'training'  =>  $db->get('training', '*', [
            'id' => $training_id
        ])
    );

    return parse($req, $res, $data);
});


