<?php

//including database handler
require('key.php');

//encrypting the id
require('hash.php');

// Sets the response data format
require('formats.php');

// Additional internal functions
require('helpers.php');




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

    //adding hordes tp data
    $data['hordes'] = $hordes;

    //getting berries by stat
    if($progress > 0)
        $data['berries'] = getBerries(array('stat' => $stat_name));

    //getting vitamins by stat
    if($progress < 100)
        $data['vitamins'] = getVitamins(array('stat' => $stat_name));



    return $data;
}

