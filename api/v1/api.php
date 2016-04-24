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

// GET vitamins
$app->get('/v1/vitamins[.{format}]', function($req, $res){

    $data = getVitamins($req->getQueryParams());
    
   return $res->write(parse($data));
});

// GET berries
$app->get('/v1/berries[.{format}]', function($req, $res){

    $data = getBerries($req->getQueryParams());
    
   return $res->write(parse($data));
});

//GET items
$app->get('/v1/items[.{format}]', function($req, $res){

    $data = getPowerItem($req->getQueryParams()['id'], $req->getQueryParams()['stat']);

    return $res->write(parse($data));

});

//GET Status
$app->get('/v1/status[.{format}]', function($req, $res){})

    $data = getStatus();

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
            global $hashids;
            $data = getTrainings($hashids->decode($req->getAttribute('id'))[0]);

            if(!sizeof($data)) {
                return $res
                    ->write(parse(["No training found."]))
                    ->withStatus(404);
            }

            return $res
                ->write(parse($data));
        });

        //GET trainings/:id/:stat
        $this->get('/{stat}[.{format}]', function($req, $res){

            global $hashids;

            $data = getTrainingsByStat($hashids->decode($req->getAttribute('id'))[0], $req->getAttribute('stat'));

            if(isset($data['errors'])){

                return $res
                    ->write(parse($data['errors']))
                    ->withStatus(400);
            }
            else{

                return $res
                    ->write(parse($data))
                    ->withStatus(200);
            }

        });


        // Group: trainings/:id/records
        $this->group('/records', function() {
            
            // GET trainings/:id/records
            $this->get('[.{format}]', function($req, $res) {
                global $hashids;

                $data = getRecords($hashids->decode($req->getAttribute('id'))[0]);

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
                global $db, $hashids;

                $data = getRecords($hashids->decode($req->getAttribute('id'))[0], $req->getAttribute('stat'));

                if(!$data) {
                    return $res
                        ->write(parse(["No training for that training in the ". $req->getAttribute('stat') ." stat."]))
                        ->withStatus(404);
                }

                return $res
                    ->write(parse($data));
            });

            
        });

        //GET trainings/:id/actions/:stat
        $this->get('/actions/{stat}[.{format}]', function($req, $res){

            global $hashids;

            $training_id = $hashids->decode($req->getAttribute('id'))[0];
            $stat = $req->getAttribute('stat');

            if(!isStat($stat) || empty(getTrainings($trainings_id)))
                return $res
                    ->write(parse(["Hey! No data available on the ". $req->getAttribute('stat')." stat."]))
                    ->withStatus(404);

            $data = getActionsByStat($training_id, $stat);

            if(!$data){
                return $res
                    ->write(parse(["Something's wrong."]))
                    ->withStatus(404);
            }
            
            return $res
                ->write(parse($data));
        });

        //GET trainings/:id/summary
        $this->get('/summary[.{format}]', function($req, $res){

            global $hashids;

            $data = getTrainingSummary($hashids->decode($req->getAttribute('id'))[0]);

            //if array, the data is an errors array, otherwise is a json decoded object with all the data required
            if(isset($data['error'])){
                return $res
                    ->write(parse($data['error']))
                    ->withStatus(404);
            }
            else{

                return $res
                    ->write(parse($data))
                    ->withStatus(200);
            }
            
        });

    });

});


// POST trainings
$app->post('/v1/trainings[.{format}]', function($req, $res) {
    global $hashids, $db, $STATS;

    // Data received
    $vars = $req->getParsedBody();
    // Data to be inserted
    $insert = [
        'timestamp' => time()
    ];
    // Errors
    $errors = [];

    // Optional parameters
    $optional = [
        'id_user', 
        'game', 
        'pokerus', 
        'power_brace', 
        'sturdy_object', 
        'timestamp'
    ];

    // Go through required
    $total = 0;
    foreach($STATS as $stat) {
        // Required must be positive
        if($vars[$stat] > 0 && $vars[$stat] <= 252) {
            $insert[$stat] = $vars[$stat];
            $total += $vars[$stat];
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

    $insert['pokerus'] = isset($vars['pokerus']) ? intval($vars['pokerus']) : 0;
    $insert['has_power_item'] = isset($vars['has_power_item']) ? intval($vars['has_power_item']) : 0;

    // Create training
    $training_id = $db->insert('training', $insert);

    // TODO: If failed, add error and return with "server" error code

    // Get full result to return
    $data = getTrainings($training_id);

    return $res
        ->write(parse($data))
        ->withStatus(201)
        ->withHeader('Location', '/v1/trainings/'.$hashids->encode($training_id));
});


//Records

// POST trainings/:id/records
$app->post('/v1/trainings/{id}/records[.{format}]', function($req, $res){
     global $hashids;

    //getting the id
    $id = $hashids->decode($req->getAttribute('id'))[0];

    //validation
    $data = postRecord($id, $req->getParsedBody());

    if(is_array($data)){

        //validation error
        return $res
            ->write(parse($data))
            ->withStatus(400);
    }
    else{

        return $res
            ->write(parse($data))
            ->withStatus(201);
    }


});



// DELETE /trainings/:id
$app->delete('/v1/trainings/{id}[.{format}]', function($req, $res) {
    global $hashids;

    $data = deleteTraining($hashids->decode($req->getAttribute('id'))[0]);

    if($data){
        return $res
            ->write(parse($data))
            ->withStatus(204);
    }
    else{

        return $res
            ->withStatus(400);
    }
});

//DELETE /records/:id
$app->delete('/v1/records/{id}[.{format}]', function($req, $res){
    global $hashids;

    $data = deleteRecord($hashids->decode($req->getAttribute('id'))[0]);

    if($data){

        return $res
            ->write(parse($data))
            ->withStatus(204);
    }
    else{

        return $res
            ->withStatus(400);
    }

});


//PATCH trainings/:id
$app->patch('/v1/trainings/{id}[.{format}]', function($req, $res){

    //variables
    global $hashids;

    //executing
    $data = updateTrainingValue($hashids->decode($req->getAttribute('id'))[0], $req->getQueryParams());

    if(is_array($data)){
        //validation error
        return $res
            ->write(parse($data))
            ->withStatus(400);
    }
    else{
        return $res
            ->write(parse($data))
            ->withStatus(201);
    }
    
});

