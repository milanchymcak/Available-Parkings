<?php
/**
 * Init
 */
include('incl/init.php');

// Set json
header('Content-Type: application/json; charset=utf-8');

// Status
$status = 'error';
if(!empty($nearestParkinSpots)) $status = 'success';

// Json
$json = [];
$json['status'] = $status;
$json['ip'] = $visitorIP;
$json['results'] = $nearestParkinSpots;

// Return
echo json_encode($json);