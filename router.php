<?php


// 404 error - Endpoint not found
$c = $app->getContainer();
$c['notFoundHandler'] = function ($c) {
    return function ($request, $response) use ($c) {
        return $c['view']->render($c['response'], 'error.html', [
            'errors' => ['Page not found']
        ])->withStatus(404);
    };
};
// 405 - Method not allowed for that endpoint, but others exist
$c['notAllowedHandler'] = function ($c) {
    return function ($request, $response, $methods) use ($c) {
        return $c['view']->render($c['response'], 'error.html', [
            'errors' => ['There was a problem with the server']
        ])->withStatus(405);
    };
};

// HOMEPAGE
$app->get('/', function ($req, $res, $args) {
    return $this->view->render($res, 'homepage.html');
})->setName('homepage');

// HOMEPAGE FORM SENT
$app->post('/', function ($req, $res, $args) {
	// POST trainings

	return $this->view->render($res, 'training.html', [
        'id_training' => $args['id'],
        'current_stat' => $args['stat']
    ]);
});


// TRAINING PAGE
$app->get('/training/{id}/{stat}', function ($req, $res, $args) {    
    // GET trainings/:id
    $training = EVs::getTrainings($args['id']); 
    // GET trainings/:id/records/:stat
    $records = EVs::getRecords($args['id'], $args['stat']);
    // GET trainings/:id/actions/:stat
    $actions = '';

    // Training not found
    if($training->stat == 'error') {
        return $this->view->render($res, 'error.html', [
            'errors' => $training->errors
        ])->withStatus(404);
    }

    // Stat is not a target
    if(!isset($training->data->target->$args['stat'])) {
        return $this->view->render($res, 'error.html', [
            'errors' => ["The stat ".$args['stat']." isn't valid"]
        ])->withStatus(404);
    }


    return $this->view->render($res, 'training.html', [
        'id_training' => $args['id'],
        'current_stat' => $args['stat'],
        'training_data' => $training,
        'records_data' => $records
    ]);
})->setName('training_stat');
