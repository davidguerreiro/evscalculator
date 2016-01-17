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

    return in_array($game, $game_list);
}


function getHordes($stat = false, $game = false) {

    // Grab JSON and make it an array of objects
    $ret = json_decode(file_get_contents('./v1/data/hordes.json'));

    // Filter by stat
    if(isStat($stat)) {
        $ret = array_filter($ret, function($a) use($stat) {
            return $a->stat_name == $stat;
        });
    }

    // Filter by game
    if(is_numeric($game) && isGame($game)) {
        $ret = array_filter($ret, function($a) use($game) {
            return $a->game == $game;
        });
    }

    // Remove original indexes
    $ret = array_values($ret);

    return $ret;
}