<?php
require 'lib/functions.php';
require 'lib/middleware.php';


$app->add(new EvsMiddleware());



// Generic
$app->get('/', function($req, $res){
    return $res->withStatus(301)->withHeader('Location', 'https://evscalculator.com');
});

// 404 error - Endpoint not found
$c = $app->getContainer();
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->write(json_encode(["Endpoint not found/available."]))
            ->withStatus(404);
    };
};


// GET hordes
$app->get('/v1/hordes[.{format}]', function($req, $res) {
  
    $data = getHordes($req->getQueryParams());

    return $res->write(json_encode($data));
 });

// GET berries
$app->get('/v1/berries[.{format}]', function($req, $res){

    $data = getBerries($req->getQueryParams());
    var_dump($data);
    die();
});


// Group: trainings
$app->group('/v1/trainings', function() {

    // GET trainings
    $this->get('[.{format}]', function($req, $res) {
        include_once('lib/key.php');

        $data = $db->select('training', '*');

        return $res->write(json_encode($data));
    });

    // Group: trainings/:id
    $this->group('/{id}', function() {
        
        // GET trainings/:id
        $this->get('[.{format}]', function($req, $res) {
            include_once('lib/key.php');

            $data = $db->get('training', '*', [
                'id' => $req->getAttribute('id')
            ]);

            return $res->write(json_encode($data));
        });

        // GET trainings/:id/records
        $this->get('/records[.{format}]', function($req, $res) {
            include_once('lib/key.php');

            $data = $db->select('records', '*', [
                'id_training' => $req->getAttribute('id')
            ]);

            return $res->write(json_encode($data));
        });

    });

});


// POST trainings
$app->post('/v1/trainings[.{format}]', function($req, $res) {
    // Data received
    $vars = $req->getParsedBody();
    // Data to be inserted
    $insert = array();
    // Errors
    $errors = array();

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
        $errors[] = "Requires at least one stat to be positive";
        // Return with validation error code
    }

    // Go through optional
    foreach($optional as $i) {
        if(!empty($vars[$i])) $insert[$i] = $vars[$i];
    }

    include_once('lib/key.php');
    include_once('lib/hash.php');

    // Create training
    $training_id = $db->insert('training', $insert);

    // If failed, add error and return with "server" error code

    $hash_url = $hashids->encode($training_id);

    // Update with URL hash
    $db->update('training', [
        'id_url' => $hash_url
    ], [
        'id' => $training_id
    ]);

    // Get full result to return
    $data = $db->get('training', '*', [
        'id' => $training_id
    ]);

    return $res->write(json_encode($data))->withStatus(201)->withHeader('Location', '/v1/trainings/'.$hash_url);
});

// DELETE /trainings/:id
$app->delete('/v1/trainings/{id}[.{format}]', function($req, $res) {
    $data = array();

    return $res->write(json_encode($data));
});

