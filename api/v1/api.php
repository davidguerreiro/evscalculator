<?php
require 'lib/parser.php';
require 'lib/functions.php';



// Generic
$app->get('/', function($req, $res){
    return $res->withStatus(301)->withHeader('Location', 'https://evscalculator.com');
});


// GET hordes
$app->get('/v1/hordes[.{format}]', function($req, $res) {
  
    $data = getHordes($req->getQueryParams());
      
    return parse($req, $res, $data);
 });

// GET berries
$app->get('/v1/berries[.{format}]', function($req, $res){

    $data = getBerries($req->getQueryParams());

    return parse($req, $res, $data);
}

// Group: trainings
$app->group('/v1/trainings', function() {

    // GET trainings
    $this->get('[.{format}]', function($req, $res) {
        include_once('lib/key.php');

        $data = $db->select('training', '*');

        return parse($req, $res, $data);
    });

    // Group: trainings/:id
    $this->group('/{id}', function() {
        
        // GET trainings/:id
        $this->get('[.{format}]', function($req, $res) {
            include_once('lib/key.php');

            $data = array(
                'stat'      =>  'success',
                'training'  =>  $db->get('training', '*', [
                    'id' => $req->getAttribute('id')
                ])
            );

            return parse($req, $res, $data);
        });

        // GET trainings/:id/records
        $this->get('/records[.{format}]', function($req, $res) {
            include_once('lib/key.php');

            $data = $db->select('records', '*', [
                'id_training' => $req->getAttribute('id')
            ]);

            return parse($req, $res, $data);
        });

    });

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

    // TODO: CREATE URL HERE

    include_once('lib/key.php');
    $training_id = $db->insert('training', $insert);

    $data = array(
        'stat'      =>  'success',
        'training'  =>  $db->get('training', '*', [
            'id' => $training_id
        ])
    );

    return parse($req, $res, $data)->withStatus(201)->withHeader('Location', '/v1/trainings/'.$training_id);
});


