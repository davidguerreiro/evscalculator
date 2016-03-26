<?php

function isStat($stat) {
    if(!$stat) return false;

    $stat_list = array(
        'hp', 
        'attack', 
        'defense', 
        'spattack', 
        'spdefense', 
        'speed'
    );

    return in_array($stat, $stat_list);
}


function isGame($game) {

    $game_list = array(
        0,
        1
    );

    return is_numeric($game) && in_array($game, $game_list);
}


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

function parse($data) {
    return json_encode($data, JSON_NUMERIC_CHECK);
}
