<?php

//including database handler
require('key.php');

//encrypting the id
require('hash.php');

$STATS = [
    'hp', 
    'attack', 
    'defense', 
    'spattack', 
    'spdefense', 
    'speed'
];

$GAMES = [
    0,
    1
];



//checking if it is a stat
function isStat($stat) {
    return (!!$stat) && in_array($stat, $STATS);
}

//checking  if is a game
function isGame($game) {
    return is_numeric($game) && in_array($game, $GAMES);
}

//getting progress value
function getProgress($stat, $training_id){

    global $db;
    $progress = 0;

    //getting values stats
    $data = $db->sum('records', 'stat_value', [
        'id_training' => $training_id
    ]);

    return intval($progress);
}

//getting hordes
function getHordes($params) {
    // Grab JSON and make it an array of objects
    $ret = json_decode(file_get_contents('./v1/data/hordes.json'));

    // Filter by stat
    if(isStat($params['stat'])) {
        $ret = array_filter($ret, function($a) use($params) {
            return $a->stat_name == $params['stat'];
        });
    }

    // Filter by game
    if(isGame($params['game'])) {
        $ret = array_filter($ret, function($a) use($params) {
            return $a->game == $params['game'];
        });
    }

    // Remove original indexes
    return array_values($ret);
}

function getBerries($params){

    // Grab JSON and make it an array of objects
    $ret = json_decode(file_get_contents('./v1/data/berries.json'));

    
    // Filter by stat
    if(isStat($params['stat'])) {
        $ret = array_filter($ret, function($a) use($params) {
            return $a->stat_name == $params['stat'];
        });
    }

    return array_values($ret);
}

function getTrainings($req, $res){   
    global $db, $hashids, $STATS;

    // WHERE parameters
    $where = array();
    // Temporary array
    $parse_data = array();
    // Returned data
    $data = array();

    // If it's /:id then filter by it
    if($req->getAttribute('id')) {
        $where = [
            'id' => $hashids->decode($req->getAttribute('id'))
        ];
    }

    // Getting trainings from the db
    $trainings = $db->select('training', '*', $where);

    // 404 IF NO TRAININGS
    if(!$trainings) {
        return $res
            ->write(parse(["Endpoint not found/available."]))
            ->withStatus(404);
    }

    // Building the object
    foreach($trainings as $element){

        // Single fields
        $parse_data['id'] = $hashids->encode($element['id']);
        $parse_data['game'] = intval($element['game']);
        $parse_data['pokerus'] = (intval($$element['pokerus']) == 0) ? false : true;
        $parse_data['sturdy_object'] = (intval($element['sturdy_object']) == 0) ? false : true;
        $parse_data['timestamp'] = $element['timestamp'];

        // Target / progress objects
        $parse_data['target'] = array();
        $parse_data['progress'] = array();

        foreach($STATS as $stat) {
            $parse_data['target'][$stat] = intval($element[$stat]);
            $parse_data['progress'][$stat] = (intval($element[$stat]) > 0) ? getProgress($stat, intval($element['id'])) : 0;
        }

        // Add from temporary array to FINAL data
        $data[] = (empty($parse_data)) ? $parse_data : (object) $parse_data;
    }

    // If there's only one result, return that first object of the array
    $data = sizeof($data) > 1 ? $data : $data[0];

    return $res
        ->write(parse($data));
}



function parse($data) {
    return json_encode($data, JSON_NUMERIC_CHECK);
}
