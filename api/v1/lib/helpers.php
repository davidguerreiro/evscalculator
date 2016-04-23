<?php

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
    global $STATS;
    return (!!$stat) && in_array($stat, $STATS);
}

//checking  if is a game
function isGame($game) {
    global $GAMES;
    return is_numeric($game) && in_array($game, $GAMES);
}

//getting progress value
function getProgress($stat, $training_id){
    global $db;

    //getting values stats
    $progress = $db->sum('records', 'stat_value', [
        'AND' => [
            'id_training' => $training_id,
            'stat_name' => $stat
        ]
    ]);

    return intval($progress);
}

function getTarget($stat, $training_id) {
    global $db;

    $target = $db->get('training', $stat, [
        'AND' => [
            'id' => $training_id
        ]
    ]);

    return intval($target);
}

function getLeft($stat, $training_id) {
    return (getTarget($stat, $training_id) - getProgress($stat, $training_id));
}


//getting hordes
function getHordes($params = null, $id = null) {
    // Grab JSON and make it an array of objects
    $ret = json_decode(file_get_contents('./v1/data/hordes.json'));

    // Just one horde
    if($id) {
        $find_value = array_values(array_filter($ret, function($a) use($id) {
            return $a->id == $id;
        }));

        return count($find_value) ? $find_value[0] : null;
    }

    // Filter by stat
    if(isStat($params['stat'])) {
        $ret = array_filter($ret, function($a) use($params) {
            return $a->stat_name == $params['stat'];
        });
    }

    // Filter by game
    if(isGame($params['game'])) {
        $ret = array_filter($ret, function($a) use($params) {
            return in_array($params['game'], $a->game);
        });
    }

    // Remove original indexes
    return array_values($ret);
}


function getBerries($params = null, $id = null){

    // Grab JSON and make it an array of objects
    $ret = json_decode(file_get_contents('./v1/data/berries.json'));

    // Just one berry
    if($id) {
        $find_value = array_values(array_filter($ret, function($a) use($id) {
            return $a->id == $id;
        }));

        return count($find_value) ? $find_value[0] : null;
    }
    
    // Filter by stat
    if(isStat($params['stat'])) {
        $ret = array_filter($ret, function($a) use($params) {
            return $a->stat_name == $params['stat'];
        });
    }

    return array_values($ret);
}


function getVitamins($params = null, $id = null){

    // Grab JSON and make it an array of objects
    $ret = json_decode(file_get_contents('./v1/data/vitamins.json'));

    // Just one vitamin
    if($id) {
        $find_value = array_values(array_filter($ret, function($a) use($id) {
            return $a->id == $id;
        }));

        return count($find_value) ? $find_value[0] : null;
    }
    
    // Filter by stat
    if(isStat($params['stat'])) {
        $ret = array_filter($ret, function($a) use($params) {
            return $a->stat_name == $params['stat'];
        });
    }

    return array_values($ret);
}

function getPowerItem($id = null, $stat = null) {
    // Grab JSON and make it an array of objects
    $ret = json_decode(file_get_contents('./v1/data/items.json'));

    if($id !== null) {
        $find_value = array_values(array_filter($ret, function($a) use($id) {
            return $a->id == $id;
        }));

        return count($find_value) ? $find_value[0] : false;
    }

    // Filter by stat
    if($stat !== null && isStat($stat)) {
        $ret = array_filter($ret, function($a) use($params) {
            return $a->stat_name == $params['stat'];
        });
    }

    return array_values($ret);
}