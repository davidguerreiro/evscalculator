<?php


// 404 error - Endpoint not found
$c = $app->getContainer();
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($c['response'], 'error.twig', [
            'errors' => ['Page not found']
        ])->withStatus(404);
    };
};
// 405 - Method not allowed for that endpoint, but others exist
$c['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['view']->render($c['response'], 'error.twig', [
            'errors' => ['There was a problem with the server']
        ])->withStatus(405);
    };
};


// HOMEPAGE
$app->get('/', function ($req, $res, $args) {
    return $this->view->render($res, 'homepage.twig');
})->setName('homepage');


// HOMEPAGE FORM SENT
$app->post('/', function ($req, $res, $args) {
	// POST trainings
    var_dump($req->getParsedBody());
    die();


    return $this->view->render($res, 'homepage.twig', [

    ]);

});


// TRAINING PAGE
$app->get('/training/{id}/{stat}', function ($req, $res, $args) {   
    global $STATS; 
    // GET trainings/:id
    $training = EVs::getTrainings($args['id']); 
    // GET trainings/:id/records/:stat
    $records = EVs::getRecords($args['id'], $args['stat']);
    // GET trainings/:id/actions/:stat
    $actions = '';

    // Training not found
    if($training->stat == 'error') {
        return $this->view->render($res, 'error.twig', [
            'errors' => $training->errors
        ])->withStatus(404);
    }

    // Stat is not a target
    if(!isset($training->data->target->$args['stat'])) {
        return $this->view->render($res, 'error.twig', [
            'errors' => ["The stat ".$args['stat']." isn't valid"]
        ])->withStatus(404);
    }

    // Build completed target percentage object
    $training->data->completed = new stdClass();
    foreach(array_keys($STATS) as $stat) {
        $training->data->completed->$stat = number_format(($training->data->progress->$stat / $training->data->target->$stat) * 100);
    }


    return $this->view->render($res, 'training.twig', [
        'id_training' => $args['id'],
        'current_stat' => $args['stat'],
        'training_data' => $training,
        'records_data' => $records
    ]);
})->setName('training_stat');
