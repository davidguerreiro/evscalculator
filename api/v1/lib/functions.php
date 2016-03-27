<?php

//including database handler
require('key.php');

//encrypting the id
require('hash.php');

// Sets the response data format
require('formats.php');

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


function parse($data) {
    return json_encode($data, JSON_NUMERIC_CHECK);
}

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


function getTrainings($id = null){   
    global $db, $hashids, $STATS;

    // WHERE parameters
    $where = array();
    // Returned data
    $data = array();

    // If it's /:id then filter by it
    if($id !== null) {
        $where = [
            'id' => $hashids->decode($id)
        ];
    }

    // Getting trainings from the db
    $trainings = $db->select('training', '*', $where);

    // Building the object
    foreach($trainings as $element){
        $data[] = formatTraining($element);
    }

    // If there's only one result, return that first object of the array
    $data = sizeof($data) > 1 ? $data : $data[0];

    return $data;
}



