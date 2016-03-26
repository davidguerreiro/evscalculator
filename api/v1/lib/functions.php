<?php

//including database handler
require('key.php');

//encrypting the id
require('hash.php');


//checking if it is a stat
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

//checking  if is a game
function isGame($game) {

    $game_list = array(
        0,
        1
    );

    return is_numeric($game) && in_array($game, $game_list);
}

//getting progress value
function getProgress($stat, $training_id){

    global $db;
    $progress = 0;

    //getting values stats
    $data = $db->select('records', 'stat_value', [
        'id_training' => $training_id
        ]);

    //getting the sum of all the evs added in this stat
    $progess = (empty($data)) ? 0 : array_sum($data);

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

function getTrainings(){   

    //variables
    global $db, $hashids;
    $parse_data = array();

    //getting all the trainings from the db
    $data = $db->select('training', '*');
    $length = count($data);

    //looping every training
    for($i = 0; $i < $length; $i++){

        //getting the id related url
        $hash_url = $hashids->encode($data[$i]['id']);

        //building the object
        $parse_data[$i]['id'] = $hash_url;
        $parse_data[$i]['game'] = intval($data[$i]['game']);
        $parse_data[$i]['pokerus'] = (intval($data_[$i]['pokerus']) == 0) ? false : true;
        $parse_data[$i]['sturdy_object'] = (intval($data[$i]['sturdy_object']) == 0) ? false : true;
        $parse_data[$i]['timestamp'] = $data[$i]['timestamp'];

        //target
        $parse_data[$i]['target'] = array(
            'hp' => intval($data[$i]['hp']),
            'attack' => intval($data[$i]['attack']),
            'defense' => intval($data[$i]['defense']),
            'spattack' => intval($data[$i]['spattack']),
            'spdefense' => intval($data[$i]['spdefense']),
            'speed' => intval($data[$i]['speed'])
        );

        //getting all progress values

        //hp
            $progress_hp = (intval($data[$i]['hp']) > 0) ? getProgress('hp', intval($data[$i]['id'])) : 0;
        //attack
            $progress_attack = (intval($data[$i]['attack']) > 0) ? getProgress('attack', intval($data[$i]['id'])) : 0;
        //defense
            $progress_defense = (intval($data[$i]['defense']) > 0) ? getProgress('defense', intval($data[$i]['id'])) : 0;
        //spattack
            $progress_spattack = (intval($data[$i]['spattack']) > 0) ? getProgress('spattack', intval($data[$i]['id'])) : 0;
        //spdefense
            $progress_spdefense = (intval($data[$i]['spdefense']) > 0) ? getProgress('spdefense', intval($data[$i]['id'])) : 0;
        //speed
            $progress_speed = (intval($data[$i]['speed']) > 0) ? getProgress('speed', intval($data[$i]['id'])) : 0;

        //progress
        $parse_data[$i]['progress'] = array(
            'hp' => $progress_hp,
            'attack' => $progress_attack,
            'defense' => $progress_defense,
            'spattack' => $progress_spattack,
            'spdefense' => $progress_spdefense,
            'speed' => $progress_speed
        );

    }

    return $parse_data;

}

function getTrainingsById($id){

    //variables
    global $db, $hashids;
    $parse_data = array();

    //getting the trainings
    $data = $db->select('training', '*', [
        'id_url' => $id
    ]);

    //building the object

    foreach($data as $element){

        //getting the id related url
        $hash_url = $hashids->encode($element['id']);

        //building the object
        $parse_data['id'] = $hash_url;
        $parse_data['game'] = intval($element['game']);
        $parse_data['pokerus'] = (intval($$element['pokerus']) == 0) ? false : true;
        $parse_data['sturdy_object'] = (intval($element['sturdy_object']) == 0) ? false : true;
        $parse_data['timestamp'] = $element['timestamp'];

        //target
        $parse_data['target'] = array(
            'hp' => intval($element['hp']),
            'attack' => intval($element['attack']),
            'defense' => intval($element['defense']),
            'spattack' => intval($element['spattack']),
            'spdefense' => intval($element['spdefense']),
            'speed' => intval($element['speed'])
        );

        //getting all progress values

        //hp
            $progress_hp = (intval($element['hp']) > 0) ? getProgress('hp', intval($element['id'])) : 0;
        //attack
            $progress_attack = (intval($element['attack']) > 0) ? getProgress('attack', intval($element['id'])) : 0;
        //defense
            $progress_defense = (intval($element['defense']) > 0) ? getProgress('defense', intval($element['id'])) : 0;
        //spattack
            $progress_spattack = (intval($element['spattack']) > 0) ? getProgress('spattack', intval($element['id'])) : 0;
        //spdefense
            $progress_spdefense = (intval($element['spdefense']) > 0) ? getProgress('spdefense', intval($element['id'])) : 0;
        //speed
            $progress_speed = (intval($element['speed']) > 0) ? getProgress('speed', intval($element['id'])) : 0;

        //progress
        $parse_data['progress'] = array(
            'hp' => $progress_hp,
            'attack' => $progress_attack,
            'defense' => $progress_defense,
            'spattack' => $progress_spattack,
            'spdefense' => $progress_spdefense,
            'speed' => $progress_speed
        );

    }

    //casting and checking if empty
    $object_data = (empty($parse_data)) ? $parse_data : (object) $parse_data;

    return $object_data;
}

function parse($data) {
    return json_encode($data, JSON_NUMERIC_CHECK);
}
