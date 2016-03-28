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


//getting hordes
function getHordes($params = null, $id = null) {
    // Grab JSON and make it an array of objects
    $ret = json_decode(file_get_contents('./v1/data/hordes.json'));

    // Just one horde
    if($id) {
        $find_value = array_values(array_filter($ret, function($a) use($id) {
            return $a->id == $id;
        }));

        return $find_value[0];
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
            return $a->game == $params['game'];
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

        return $find_value[0];
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

        return $find_value[0];
    }
    
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
        $where['id'] = $hashids->decode($id);
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


function getRecords($training_id, $stat = null) {
    global $db, $hashids, $STATS;
    $data = array();
    $where = [
        'AND' => [
            'id_training' => $hashids->decode($training_id)[0]
        ]
    ];

    // If we only want records for one stat
    if($stat !== null) {
        $where['AND']['stat_name'] = $stat;
        $recordList = $db->select('records', '*', $where);

        if($recordList) {
            foreach($recordList as $record) {
                $data[] = formatRecord($record);
            }
        }
    } else {
        // We need one array per stat with target
        foreach($STATS as $stat) {
            $where['AND']['stat_name'] = $stat;
            $recordList = $db->select('records', '*', $where);

            // If there are records for that stat
            if($recordList) {
                // Create stat property in data
                $data[$stat] = array();
                foreach($recordList as $record) {
                    // Add record to stat in data
                    $data[$stat][] = formatRecord($record);
                }
            }
        }
        $data = (empty($data)) ? $data : (object) $data;
    }

    return $data;
}

//validation
function validatePostParams($id, $params){

    //variables
    global $db, $STATS;



    //validation
    if(!isset($params) || !is_array($params)){
        $validate_data['error'] = 'No data received';
        return $validate_data;
    }

    if(!is_int($id) || $id < 0){
        $validate_data['error'] = 'Invalid Id or ID not properly decoded';
        return $validate_data;
    }

    //required
    if(!isset($params['value']) || !isset($params['stat'])){
        $validate_data['error'] = 'Required data no received';
        return $validate_data;
    }
    else{
        $value = intval($params['value']);
        $stat = $params['stat'];
    }

    //validating stats

    //check if the stat name is valid
    if(!in_array($stat, $STATS)){
        $validate_data['error'] = 'Invalid stat name';
        return $validate_data;
    }

    if($value < -10 || $value > 252){
        $validate_data['error'] = 'Invalida value number';
        return $validate_data;
    }

    //from
    if(isset($params['from'])){
        $explode = explode(':', $params['from']);
        $from_text = $explode[0];
        $from_value = intval($explode[1]);

        //from validation
        if($from_text !== 'horde' && $from_text !== 'vitamin' && $from_text !== 'berry'){
            $validate_data['error'] = 'invalid from text value';
            return $validate_data;
        }

        if(!is_numeric($from_value)){
            $validate_data['error'] = 'invalid from id value';
            return $validate_data;
        }

    }
    else
        $from_value = false;

    //non required parameters
    $game = (isset($params['game'])) ? intval($params['game']) : 0;
    $pokerus = (isset($params['pokerus'])) ? intval($params['pokerus']) : 0;

    if(!is_int($game) || !is_int($pokerus) || !is_numeric($game) || !is_numeric($pokerus)){
        $validate_data['error'] = 'No required data error invalid format';
        return $validate_data;
    }

    //building the insert array
    $array_insert = array(
        'id_training' => $id,
        'stat_name' => $stat,
        'stat_value' => $value,
        'game' => $game,
        'pokerus' => $pokerus
    );

    //checking if from value has to be added
    if($from_value !== false){

        $array_from = array('id_'.$from_text => $from_value);

        $array_insert = array_merge($array_insert, $array_from);

    }
    
    //insert data
    $record_id = intval($db->insert('records', $array_insert));

    //getting the last insert data
    $last_insert = $db->get('records',
        '*',['id' => $record_id]
    );

    return (object) $last_insert;

}