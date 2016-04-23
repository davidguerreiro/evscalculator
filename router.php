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
    $new_training = EVs::postTraining($req->getParsedBody());

    if($new_training->stat == "error") {
        return $this->view->render($res, 'homepage.twig', [
            "errors" => $new_training->errors
        ]);
    }
    $training = $new_training->data;

    return $res->withHeader('Location', '/training/'.$training->id.'/attack');
    
});


// TRAINING PAGE
$app->get('/training/{id}/{stat}', function ($req, $res, $args) {   
    global $STATS; 
    // GET trainings/:id
    $training = EVs::getTrainings($args['id']); 
    // GET trainings/:id/records/:stat
    $records = EVs::getRecords($args['id'], $args['stat']);
    // GET trainings/:id/actions/:stat
    $actions = EVs::getActions($args['id'], $args['stat']);
    // GET items for this stat
    $items = EVs::getPowerItems($args['stat']);

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
    $training->data->left = new stdClass();
    foreach(array_keys($STATS) as $stat) {
        $training->data->completed->$stat = number_format(($training->data->progress->$stat / $training->data->target->$stat) * 100);
        $training->data->left->$stat = ($training->data->target->$stat - $training->data->progress->$stat);
    }

    // Add current item to 


    return $this->view->render($res, 'training.twig', [
        'id_training' => $args['id'],
        'current_stat' => $args['stat'],
        'training_data' => $training,
        'records_data' => $records,
        'actions_data' => $actions,
        'items_data' => $items,

        'training' => $training->data,
        'records' => $records->data,
        'actions' => $actions->data,

        'left' => $training->data->left->$args['stat'],
        'gained' => $training->data->progress->$args['stat'],
        'target' => $training->data->target->$args['stat'],
        'percentage' => $training->data->completed->$args['stat']
    ]);
})->setName('training_stat');


$app->post('/training/{id}/{stat}', function ($req, $res, $args) { 
    global $STATS; 
    
    // POST vars
    $vars = $req->getParsedBody();

     switch($vars['action']) {
        case 'add':
            $new_record = EVs::postRecord($vars['id'], $vars);

            if($new_record->stat == "error") {
                var_dump($new_record->errors);
                die();
            }
            break;
        case 'change_item':
            $training_with_new_item = EVs::setPowerItem($args['id'], $vars['power_item']);

            if($training_with_new_item->stat == "error") {
                var_dump($training_with_new_item->errors);
                die();
            }
            break;

        case 'activate_pokerus':
            $training_with_pokerus = EVs::enablePokerus($args['id']);

            if($training_with_pokerus->stat == "error") {
                var_dump($training_with_pokerus->errors);
                die();
            }
            break;

        default:
    }

    return $res->withHeader('Location', '/training/'.$args['id'].'/'.$args['stat']);
});


