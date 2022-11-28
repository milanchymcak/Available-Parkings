<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

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