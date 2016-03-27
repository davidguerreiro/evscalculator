<?php

// Response format for a training
function formatTraining($element) {
	global $hashids, $STATS;
	$ret = array();

	// Single fields
    $ret['id'] = $hashids->encode($element['id']);
    $ret['game'] = intval($element['game']);
    $ret['pokerus'] = (intval($$element['pokerus']) == 0) ? false : true;
    $ret['sturdy_object'] = (intval($element['sturdy_object']) == 0) ? false : true;
    $ret['timestamp'] = $element['timestamp'];

    // Target / progress objects
    $ret['target'] = array();
    $ret['progress'] = array();

    foreach($STATS as $stat) {
        $ret['target'][$stat] = intval($element[$stat]);
        $ret['progress'][$stat] = (intval($element[$stat]) > 0) ? getProgress($stat, intval($element['id'])) : 0;
    }

    return (empty($ret)) ? $ret : (object) $ret;
}

// Response format for a record
function formatRecord($element) {
    global $hashids, $STATS;
    $ret = array();

    $ret['id'] = $hashids->encode($element['id']);
    $ret['value'] = $element['stat_value'];
    $ret['pokerus'] = ($element['pokerus']==1);
    $ret['timestamp'] = $element['timestamp'];

    if($element['id_horde']) {
        $ret['from'] = [
            "type" => "hordes",
            "origin" => getHordes($id)
        ];
    }

    if($element['id_vitamin']) {
        $ret['from'] = [
            "type" => "vitamins",
            "origin" => getVitamins($id)
        ];
    }

    if($element['id_berry']) {
        $ret['from'] = [
            "type" => "berries",
            "origin" => getBerries($id)
        ];
    }

    return (empty($ret)) ? $ret : (object) $ret;
}