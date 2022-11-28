<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


/**
 * Autoload
 */
spl_autoload_register(function ($class_name) {
    include 'classes/' . $class_name . '.php';
});

/**
 * API Test
 *
 * Paramaters: customIP, lat, lon
 */
if(isset($_POST) && isset($_POST['test'])) {

    // IP
    $custom_IPs = explode(PHP_EOL, trim($_POST['customIP']));
    $lenIps = count($custom_IPs);

    // Lat
    $lats = explode(PHP_EOL, trim($_POST['lat']));
    $lenLats = count($lats);

    // Lon
    $lons = explode(PHP_EOL, trim($_POST['lon']));
    $lenLons = count($lons);

    // Get Biggest Array To Get Max Length
    // $lats & $lots should have the same length
    $maxLen = $lenIps > $lenLats ? $lenIps : $lenLats;

    // Stack
    $stack = [];

    // Loop
    for($i = 0; $i < $maxLen; $i++) {

        $ip = '';
        if(isset($custom_IPs[$i])) $ip = trim($custom_IPs[$i]);

        // Init
        $test = new TestProject($ip);

        // Lat & Lon
        $lat = $lon = '';
        if(isset($lats[$i])) $lat = trim($lats[$i]);
        if(isset($lons[$i])) $lon = trim($lons[$i]);
        $nearestParkinSpots = $test->getDistanceBetweenPoints($lat, $lon);

        // Visitor IP
        $visitorIP = $test->getIP();

        // Save
        $stack[] = array(
            'IP' => $ip,
            'lat' => $lat,
            'lon' => $lon,
            'request' => '/api.php?customIP=' . $ip . '&lat=' . $lat . 'lon=' . $lon,
            'resultsCount' => count($nearestParkinSpots),
            'results' => $nearestParkinSpots
        );
    }

    // Print
    print("<pre>".print_r($stack,true)."</pre>");
}
?>
<style>
form {
    max-width: 600px;
    margin: 50px auto;
    text-align: center;
}
textarea {
    display: block;
    width: 100%;
    margin: 15px;
    padding: 12px;
}
input[type="submit"] {
    width: 100px;
    padding: 7px;
}
</style>
<form method="POST">
    <textarea name="customIP" placeholder="1. IP Adresa na řádek"></textarea>
    <textarea name="lat" placeholder="1. Lat. na řádek"></textarea>
    <textarea name="lon" placeholder="1. Lon. na řádek"></textarea>
    <input type="hidden" name="test" value=1 />
    <input type="submit" value="Odeslat" />
</form>
