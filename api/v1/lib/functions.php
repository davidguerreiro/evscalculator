<?php

//including database handler
require('key.php');

//encrypting the id
require('hash.php');

// Helper functions
require('helpers.php');

// Sets the response data format
require('formats.php');



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
            'records.id_training' => $training_id
        ],
        'ORDER' => 'records.timestamp DESC'
    ];
    $fields = [
        "training.pokerus",
        "records.id",
        "records.stat_value",
        "records.power_item",
        "records.id_horde",
        "records.id_vitamin",
        "records.id_berry",
        "records.timestamp"
    ];

    // If we only want records for one stat
    if($stat !== null) {
        $where['AND']['records.stat_name'] = $stat;
        $recordList = $db->select('records', [
            "[>]training" => ["id_training" => "id"]
        ], $fields, $where);

        if($recordList) {
            foreach($recordList as $record) {
                $data[] = formatRecord($record);
            }
        }
    } else {
        // We need one array per stat with target
        foreach($STATS as $stat) {
            $where['AND']['records.stat_name'] = $stat;
            $recordList = $db->select('records', [
                "[>]training" => ["id_training" => "id"]
            ], $fields, $where);

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
        'id_training' => $id,
        'timestamp' => time()
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
    $insert['power_item'] = (isset($params['power_item']) && getPowerItem($params['power_item'])) ? $params['power_item'] : 0;

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
        var_dump($record_id);
        die();
        $errors[] = "There was a problem connecting with the DB";
        return $errors;
    }

    //getting the last insert data
    $last_insert = $db->get('records', [
            "[>]training" => ["id_training" => "id"]
        ],[
            "training.pokerus",
            "records.id",
            "records.stat_value",
            "records.power_item",
            "records.id_horde",
            "records.id_vitamin",
            "records.id_berry",
            "records.timestamp"
        ],['records.id' => $record_id]
    );

    $data = formatRecord($last_insert);

    return $data;

}


//getting actions from 
function getActionsByStat($id, $stat_name){

    //testing id : kZgRZP8q91
     //variables
    global $db;
    $data = [];
    $errors = [];

    //getting the training current status
    $current_training = getTrainings($id);
    
    //getting target, progress and left
    $target = getTarget($stat_name, $id);
    $progress = getProgress($stat_name, $id);
    $left = getLeft($stat_name, $id);

    //building the first part of the array
    $data['recomended'] = [];
    $data['hordes'] = [];
    $data['vitamins'] = [];
    $data['berries'] = [];

    $pokerus = $current_training->pokerus;
    $power_item = $current_training->power_item;
    $game = $current_training->game;
    
    //getting the hordes filter by stat
    $hordes = getHordes(['stat' => $stat_name, 'game' => $game]);

    if(empty($hordes))
        return $errors;

    //looping hordes
    foreach($hordes as $horde){

        //horde_value
        $total_value = $horde->stat_value;

        //calculating the whole value of the horde
        if(is_array($power_item)){
            if($power_item['op'] == 'by')
                $total_value *= $power_item['value'];       
            else
                $total_value += $power_item['value'];
        }

        //calculating pokerus
        if($pokerus != 0)
            $total_value *= 2;

        //updating horde value
        $horde->stat_value = $total_value;

        //adding this horde to the valid horde data
        $horde->invalid = ($left < $total_value) ? true : false;
    }


    //sorting hordes by value
    usort($hordes, function($a, $b) {
        return $a->stat_value < $b->stat_value;
    });


    //adding hordes tp data
    $data['hordes'] = $hordes;

    //getting berries by stat
    if($progress > 0)
        $data['berries'] = getBerries(['stat' => $stat_name]);

    //getting vitamins by stat
    if($progress < 100)
        $data['vitamins'] = getVitamins(['stat' => $stat_name]);

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


//update a single field trougth patch
function updateTrainingValue($id, $data){

    //variables
    global $db;
    $errors = [];
    $v_data = [];

    //validation
    if(!isset($id) || !isset($data) || !is_int($id) || !is_array($data))
        return $errors;

    foreach($data as $key => $value){

        //operation
        if($key === 'op'){

            if($value !== 'test' && $value !== 'replace' && $value !== 'remove')
                return $errors;

        }

        //field
        if($key === 'field'){

            if($value !== 'pokerus' && $value !== 'power_item')
                return $errors;
        }

        //value   --   no required when operation = remove
        if($key === 'value' && ($data['op'] === 'test' || $data['op'] === 'replace')){

            if($value === '' || is_null($value))
                return $errors;

        }

    }

    //getting field
    $field = ($data['field'] === 'pokerus') ? 'pokerus' : 'power_item';

    //updating field based on operation
    if($data['op'] === 'test'){
    
        //test checks if the value stored on the db matches with the current value
        $db_value = intval($db->get('training', $field,['id' => $id]));
    
        return ($db_value === intval($data['value'])) ? getTrainings($id) : $errors;

    }
    else{
        
        //value is 0 if we reset the data
        $data['value'] = ($data['op'] === 'remove') ? 0 : intval($data['value']);

        //updating value
        $updated = $db->update('training',[$data['field'] => $data['value']], ['id' => $id]);

        return (is_numeric($updated) && is_int($updated)) ? getTrainings($id) : $errors;
    }

}

//Get training summary
function getTrainingSummary($id){


    //variables
    global $db;
    $erros = [];
    $id_items = [];

    //evs got during the training
    $evs = [
        'manual' => 0,
        'hordes' => 0,
        'vitamins' => 0,
        'berries' => 0
    ];

    //count of actions made during the training
    $times_used = [
        'manual' => 0,
        'hordes' => 0,
        'vitamins' => 0,
        'berries' => 0
    ];

    //power items used during the training
    $items_used = [];

    //getting records
    $records = getRecords($id);

    if(empty($records))
        return $errors[] = 'No data received from the API';
    
    //looping the stats
    foreach($records as $stat){

        //looping the actions made
        foreach($stat as $action){

            //check if manual
            $key = (!isset($action->from)) ? 'manual' : $action->from['type'];
            
            //adding value and times used
            $evs[$key] += $action->value;
            $times_used[$key]++;

            //adding item
            if($action->power_item !== false){

                //items are added only one time
                if(!in_array($power_item->id, $id_items)){
                    $id_items[] = $power_item->id;
                    $items_used[] = $action->power_item;
                }
            }

        }//end action foreach

    }//end records foreach

    //building final data array
    $data = [
    'evs' => $evs,
    'times_used' => $times_used,
    'items_used' => $items_used
    ];

    return $data;
}

//Delete training by id
function deleteTraining($id){

    //variables
    global $db;

    $deleted_items = $db->delete("training", [
        "AND" => [
            "id" => $id
        ]
    ]);

    return (is_numeric($deleted_items) && is_int($deleted_items)) ? true : false;
}

//Delete record by id
function deleteRecord($id){

    //variables
    global $db;

     $deleted_items = $db->delete("records", [
        "AND" => [
            "id_training" => $id
        ]
    ]);

    return (is_numeric($deleted_items) && is_int($deleted_items)) ? true : false;

}
