<?php

$app->get('/', function ($req, $res, $args) {
    return $this->view->render($res, 'homepage.html');
})->setName('profile');


$app->post('/', function ($req, $res, $args) {
	// Request to POST trainings

    return $this->view->render($res, 'homepage.html', [
        'id_training' => $args['id'],
        'current_stat' => $args['stat']
    ]);
});

$app->get('/training/{id}/{stat}', function ($req, $res, $args) {
	// Request to GET trainings/:id

    return $this->view->render($res, 'training.html', [
        'id_training' => $args['id'],
        'current_stat' => $args['stat']
    ]);
})->setName('training_stat');
