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


function getHordes($vars) {
    // Grab JSON and make it an array of objects
    $ret = json_decode(file_get_contents('./v1/data/hordes.json'));

    // Filter by stat
    if(isStat($vars['stat'])) {
        $ret = array_filter($ret, function($a) use($vars) {
            return $a->stat_name == $vars['stat'];
        });
    }

    // Filter by game
    if(is_numeric($vars['game']) && isGame($vars['game'])) {
        $ret = array_filter($ret, function($a) use($vars) {
            return $a->game == $vars['game'];
        });
    }

    /*
    // Sorting is now done through middleware

    // Order (name by default)
    if(!isset($vars['sort'])) $vars['sort'] = 'stat_value';

    usort($ret, function($a, $b) use($vars) {
        if(is_numeric($a->{$vars['sort']}) && is_numeric($b->{$vars['sort']})) {
            return $b->{$vars['sort']} - $a->{$vars['sort']};
        }
        return strcmp($a->{$vars['sort']}, $b->{$vars['sort']});
    });

    if($vars['reverse']) $ret = array_reverse($ret, true);
    */

    // Remove original indexes
    return array_values($ret);
}

function getBerries($vars){

    // Grab JSON and make it an array of objects
    $ret = json_decode(file_get_contents('./v1/data/berries.json'));

    // Filter by stat
    if(isStat($vars['stat'])) {
        $ret = array_filter($ret, function($a) use($vars) {
            return $a->stat_name == $vars['stat'];
        });
    }

}

function parse($data) {
    return json_encode($data, JSON_NUMERIC_CHECK);
}
