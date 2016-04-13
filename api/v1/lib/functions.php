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

$ITEMS = [
    1 => [
        "name" => "Macho Brace",
        "op" => "by",
        "value" => 2
    ],
    2 => [
        "name" => "Power Anklet",
        "stat" => "speed",
        "op" => "add",
        "value" => 20
    ],
    3 => [
        "name" => "Power Band",
        "stat" => "spdefense",
        "op" => "add",
        "value" => 20
    ],
    4 => [
        "name" => "Power Belt",
        "stat" => "defense",
        "op" => "add",
        "value" => 20
    ],
    5 => [
        "name" => "Power Bracer",
        "stat" => "attack",
        "op" => "add",
        "value" => 20
    ],
    6 => [
        "name" => "Power Lens",
        "stat" => "spattack",
        "op" => "add",
        "value" => 20
    ],
    7 => [
        "name" => "Power Weight",
        "stat" => "hp",
        "op" => "add",
        "value" => 20
    ]
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

function getPowerItem($id = null, $stat = null) {
    global $ITEMS;

    // If we want by ID and it exists
    if($id !== null && array_key_exists($id, $ITEMS)) {
        return $ITEMS[$id];
    }

    // Recommended power item by stat
    if($id === null && $stat !== null) {
        // Find items that fit that stat
        $find_value = array_values(array_filter($ITEMS, function($a) use($id) {
            return isset($a->stat) ? $a->stat == $stat : false;
        }));

        return count($find_value) ? $find_value[0] : null;
    }

    return false;
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

//getting the amount of evs that we need to complete the stat
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


function getTrainings($id = null){   
    global $db, $hashids, $STATS;

    // WHERE parameters
    $where = [];
    // Returned data
    $data = [];

    // If it's /:id then filter by it
    if($id !== null) {
        $where['AND']['id'] = $id;
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
    $data = [];
    $where = [
        'AND' => [
            'id_training' => $training_id
        ],
        'ORDER' => 'timestamp DESC'
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
                $data[$stat] = [];
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
function postRecord($id, $params){

    //variables
    global $db, $STATS, $hashids;

    $insert = [
        'id_training' => $id
    ];
    $errors = [];

    //required
    if(!isset($params['value']) 
        || !isset($params['stat']) 
        || $params['value'] < -10 
        || $params['value'] > 252
        || !isStat($params['stat'])){
        $errors[] = 'Stat/value not valid';
    }
    else{
        $insert['stat_name'] = $params['stat'];
        $insert['stat_value'] = intval($params['value']);
    }
  
    //from
    if(isset($params['from'])){
        $explode = explode(':', $params['from']);
        $from_text = $explode[0];
        $from_id = intval($explode[1]);

        if($from_text == 'horde') {
            $insert['id_horde'] = $from_id;
            if(!getHordes(null, $from_id)) $errors[] = "The ID '".$from_id."' doesn't match any ".$from_text;
        }
        else if($from_text == 'vitamin') {
            $insert['id_vitamin'] = $from_id;
            if(!getVitamins(null, $from_id)) $errors[] = "The ID '".$from_id."' doesn't match any ".$from_text;
        }
        else if($from_text == 'berry') {
            $insert['id_berry'] = $from_id;
            if(!getBerries(null, $from_id)) $errors[] = "The ID '".$from_id."' doesn't match any ".$from_text;
        }
        else {
            $errors[] = "Invalid record origin.";
        }
    }

    //non required parameters
    $insert['pokerus'] = (isset($params['pokerus'])) ? 1 : 0;

    // Check if value is too much for the target
    $left = getLeft($params['stat'], $id);

    if($left < intval($params['value'])) {
        $errors[] = "That's more EVs that you need.";
    }


    // If errors
    if(sizeof($errors) > 0) return $errors;

    //insert data
    $record_id = intval($db->insert('records', $insert));

    if(!$record_id){
        $errors[] = "There was a problem connecting with the DB";
        return $errors;
    }

    //getting the last insert data
    $last_insert = $db->get('records',
        '*',['id' => $record_id]
    );

    $data = formatRecord($last_insert);

    return $data;

}

//usort function to sort hordes by values
function sort_relationals_array($a, $b){
    return strcmp($a->stat_value, $b->stat_value);
}

//getting actions from 
function getActionsByStat($id, $stat_name){

    //testing id : kZgRZP8q91
     //variables
    global $db;
    $data = array();
    $errors = array();

    //getting the training current status
    $current_training = getTrainings($id);

    //validation
    if(!isStat($stat_name) || empty($current_training))
        return false;
    
    //getting target, progress and left
    $target = getTarget($stat_name, $id);
    $progress = getProgress($stat_name, $id);
    $left = getLeft($stat_name, $id);


    //building the first part of the array
    $data['recomended'] = array();
    $data['hordes'] = array();
    $data['vitamins'] = array();
    $data['berries'] = array();

    $pokerus = $current_training->pokerus;
    $power_item = $current_training->power_item;
    $game = $current_training->game;
    

    //getting the hordes filter by stat
    $hordes = getHordes(array('stat' => $stat_name, 'game' => $game));

    if(empty($hordes))
        return false;

    //looping hordes
    $valid_hordes = array();

    foreach($hordes as $horde){

        //horde_value
        $total_value = $horde->stat_value;

        //calculating the whole value of the horde
        if(is_array($power_item)){

                if($power_item['op'] == 'by')
                    $total_value = $total_value * $power_item['value'];       
                else
                    $total_value = $total_value + $power_item['value'];
                
        }

        //calculating pokerus
        if($pokerus)
            $total_value = $total_value * 2;

        //updating horde value
        $horde->stat_value = $total_value;

        //adding this horde to the valid horde data
        $horde->invalid = ($left < $total_value) ? true : false;

        
    }

    //sorting hordes by value
    usort($hordes, "sort_relationals_array");


    //adding hordes tp data
    $data['hordes'] = $hordes;

    //getting berries by stat
    if($progress > 0)
        $data['berries'] = getBerries(array('stat' => $stat_name));

    //getting vitamins by stat
    if($progress < 100)
        $data['vitamins'] = getVitamins(array('stat' => $stat_name));

    //building recomended array

    //pokerus
    if($pokerus)
        $data['recommended']['pokerus'] = true;
    else{

        //getting the total left
        $total_left = $current_training->total;

        //getting the max value
        $horde_max_value = 0;

        foreach($data['hordes'] as $horde){

            if($horde->stat_value >= $horde_max_value)
                $horde_max_value = $horde->stat_value;

        }

        //checking if the horde max value is higer than the total left
        $data['recommended']['pokerus'] = ($total_left >= $horde_max_value) ? true : false;

    }

    //power item

    return $data;
}

