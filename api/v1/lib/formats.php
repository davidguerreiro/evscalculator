<?php

// Response format for a training
function formatTraining($element) {
	global $hashids, $STATS;
	$ret = [];

	// Single fields
    $ret['id'] = $hashids->encode($element['id']);
    $ret['game'] = intval($element['game']);
    $ret['pokerus'] = (intval($element['pokerus']) == 0) ? false : true;
    $ret['power_item'] = getPowerItem($element['power_item']);
    $ret['has_power_item'] = intval($element['has_power_item']) ? intval($element['has_power_item']) : 0;
    $ret['timestamp'] = $element['timestamp'];

    // Target / progress objects
    $ret['target'] = [];
    $ret['progress'] = [];
    $ret['total'] = 0;

    foreach($STATS as $stat) {
        if(intval($element[$stat])) {
            $ret['target'][$stat] = intval($element[$stat]);
            $ret['progress'][$stat] = (intval($element[$stat]) > 0) ? getProgress($stat, intval($element['id'])) : 0;
            $ret['total'] += $ret['target'][$stat] - $ret['progress'][$stat];
        }
    }

    return (empty($ret)) ? $ret : (object) $ret;
}

// Response format for a record
function formatRecord($element) {
    global $hashids, $STATS;
    $ret = [];

    $ret['id'] = $hashids->encode($element['id']);
    $ret['value'] = $element['stat_value'];
    $ret['pokerus'] = ($element['pokerus']==1);
    $ret['timestamp'] = $element['timestamp'];

    if($element['id_horde']) {
        $ret['from'] = [
            "type" => "hordes",
            "origin" => getHordes(null, $element['id_horde'])
        ];
    }

    if($element['id_vitamin']) {
        $ret['from'] = [
            "type" => "vitamins",
            "origin" => getVitamins(null, $element['id_vitamin'])
        ];
    }

    if($element['id_berry']) {
        $ret['from'] = [
            "type" => "berries",
            "origin" => getBerries(null, $element['id_berry'])
        ];
    }

    return (empty($ret)) ? $ret : (object) $ret;
}
