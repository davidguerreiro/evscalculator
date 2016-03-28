<?php

$STATS = [
    'hp' => 'HP', 
    'attack' => 'Attack', 
    'defense' => 'Defense', 
    'spattack' =>  'Special attack', 
    'spdefense' => 'Special defense', 
    'speed' => 'Speed'
];

$GAMES = [
    0 => 'X/Y',
    1 => 'ORAS'
];


$app->get('/', function ($req, $res, $args) {
	global $STATS, $GAMES;

    return $this->view->render($res, 'homepage.html', [
        'stats' => $STATS,
        'games' => $GAMES
    ]);
})->setName('profile');


$app->post('/', function ($req, $res, $args) {
	global $STATS;
	// Request to POST trainings

	return $this->view->render($res, 'homepage.html', [
        'id_training' => $args['id'],
        'current_stat' => $args['stat'],
        'stats' => $STATS
    ]);
});


$app->get('/training/{id}/{stat}', function ($req, $res, $args) {
	// Request to GET trainings/:id
    $training = EVs::getTrainings($args['id']); // Results here

    // Request to GET trainings/:id/records/:stat
    // Request to GET trainings/:id/actions/:stat

    return $this->view->render($res, 'training.html', [
        'id_training' => $args['id'],
        'current_stat' => $args['stat'],
        'training' => $training
    ]);
})->setName('training_stat');
