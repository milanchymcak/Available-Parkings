<?php

/**
 * Autoload
 */
spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
});

/**
 * Init
 */
if(isset($_GET) && isset($_GET['customIP'])) {
    $test = new TestProject($_GET['customIP']);
} else {
    $test = new TestProject;
}

/**
 * Sorted stack of nearest parking spots by IP
 * User input lat, long or current location
 */
if(isset($_GET) && isset($_GET['lat']) && isset($_GET['lon'])) {
    $nearestParkinSpots = $test->getDistanceBetweenPoints($_GET['lat'], $_GET['lon']);
} else {
    $nearestParkinSpots = $test->getDistanceBetweenPoints();
}

// Visitor IP
$visitorIP = $test->getIP();