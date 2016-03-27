<?php
require 'lib/functions.php';
require 'lib/middleware.php';


$app->add(new EvsMiddleware());



// Generic
$app->get('/', function($req, $res){
    return $res->withStatus(301)->withHeader('Location', 'https://evscalculator.com');
});

// Fix OPTIONS on CORS
// return HTTP 200 for HTTP OPTIONS requests
$app->options('/(:x+)', function($req, $res) {
    return $res;
});

// 404 error - Endpoint not found
$c = $app->getContainer();
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['response']
            ->write(parse(["Endpoint not found/available."]))
            ->withStatus(404);
    };
};
// 405 - Method not allowed for that endpoint, but others exist
$c['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['response']
            ->withStatus(405)
            ->withHeader('Allow', implode(', ', $methods))
            ->withHeader('Content-type', 'text/html')
            ->write(parse(['Method must be one of: ' . implode(', ', $methods)]));
    };
};


// GET hordes
$app->get('/v1/hordes[.{format}]', function($req, $res) {
  
    $data = getHordes($req->getQueryParams());

    return $res->write(parse($data));
 });

// GET berries
$app->get('/v1/berries[.{format}]', function($req, $res){

    $data = getBerries($req->getQueryParams());
    
   return $res->write(parse($data));
});


// Group: trainings
$app->group('/v1/trainings', function() {

    // GET trainings
    $this->get('[.{format}]', function($req, $res) {
        $data = getTrainings();

        // 404 IF NO TRAININGS
        if(!sizeof($data)) {
            return $res
                ->write(parse(["No training found."]))
                ->withStatus(404);
        }

        return $res
                ->write(parse($data));
    });


    // Group: trainings/:id
    $this->group('/{id}', function() {
        
        // GET trainings/:id
        $this->get('[.{format}]', function($req, $res) {
            $data = getTrainings($req->getAttribute('id'));

            if(!sizeof($data)) {
                return $res
                    ->write(parse(["No training found."]))
                    ->withStatus(404);
            }

            return $res
                ->write(parse($data));
        });

        // Group: trainings/:id/records
        $this->group('/records', function() {
            
            // GET trainings/:id/records
            $this->get('[.{format}]', function($req, $res) {
                global $db;

                $data = getRecords($req->getAttribute('id'));

                if(!$data) {
                    return $res
                        ->write(parse(["No records for this training."]))
                        ->withStatus(404);
                }

                return $res
                    ->write(parse($data));
            });


            // GET trainings/:id/records/:stat
            $this->get('/{stat}[.{format}]', function($req, $res) {
                global $db;

                $data = getRecords($req->getAttribute('id'), $req->getAttribute('stat'));

                if(!$data) {
                    return $res
                        ->write(parse(["No training for that training in the ". $req->getAttribute('stat') ." stat."]))
                        ->withStatus(404);
                }

                return $res
                    ->write(parse($data));
            });

            
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
        // TODO: Return with validation error code

        return $res
            ->write(parse($errors))
            ->withStatus(400);
    }

    // Go through optional
    foreach($optional as $i) {
        if(!empty($vars[$i])) $insert[$i] = $vars[$i];
    }

    include_once('lib/key.php');
    include_once('lib/hash.php');

    // Create training
    $training_id = $db->insert('training', $insert);

    // TODO: If failed, add error and return with "server" error code

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

    return $res
        ->write(parse($data))
        ->withStatus(201)
        ->withHeader('Location', '/v1/trainings/'.$hash_url);
});


// DELETE /trainings/:id
$app->delete('/v1/trainings/{id}[.{format}]', function($req, $res) {
    $data = array();

    include_once('lib/key.php');
    $deleted_items = $db->delete("training", [
        "AND" => [
            "id_url" => $req->getAttribute('id')
        ]
    ]);

    return $res
        ->write(parse($data))
        ->withStatus(204);
});

