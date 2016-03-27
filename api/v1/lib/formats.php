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
    $ret['horde'] = $element['horde_id'] ? intval($element['horde_id']) : false;
    $ret['vitamin'] = $element['vitamin_id'] ? intval($element['vitamin_id']) : false;

    $ret['value'] = $element['stat_value'];

    $ret['timestamp'] = $element['timestamp'];

    return (empty($ret)) ? $ret : (object) $ret;
}