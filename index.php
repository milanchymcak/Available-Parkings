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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title></title>
  <link href="style.css" rel="stylesheet" />
  <script src="front.js"></script>
</head>
<body>
    <div class="searchByGPS">
        <div class="container">
            <form method="GET">
                <input type="text" required name="lat" placeholder="Země. Šířka" />
                <input type="text" required name="lon" placeholder="Země. Délka" />
                <input type="submit" value="Odeslat" />
            </form>
        </div>
    </div>
    <header>
        <div class="container">
            <div id="logo">
                Test Project
            </div>
        </div>
    </header>
    <div class="container sortBy">
        <select id="comboA" onchange="sortParkingSpots(this.value)">
            <option value="">Srovnat Výsledky</option>
            <option value="1">Nejvíce Míst</option>
            <option value="2">Nejméně Míst</option>
            <option value="3" selected>Nejbližší</option>
            <option value="4">Nejvzdálenější</option>
        </select>
    </div>
    <div class="container visitorIP">
        <span>Vaše IP Adresa:</span>
        <strong><?php echo $visitorIP; ?></strong>
        <cite onclick="ipChangeShow()">(Změnit)</cite>
        <form class="hide" method="GET">
            <input type="text" name="customIP" placeholder="Nová IP" required />
            <input type="submit" value="Změnit" />
        </form>
    </div>
    <div class="container search">
        <form>
            <input type="text" name="search" value="" placeholder="Vyhledat Parkování" />
        </form>
    </div>
    <div class="container parkingSpotsBox">
<?php

foreach($nearestParkinSpots as $parkingSpot) {

    $distanceMeter = number_format($parkingSpot['distance'], 3, '.', '')*1000;

    echo '<div class="parkingSpot" data-distance="' . $distanceMeter .'" data-spots="'.$parkingSpot['numOfFreePlaces'].'">';
    echo '<div><strong>' . $parkingSpot['name'] . '</strong></div>';
    echo '<div>Vzdálenost: ' . number_format($parkingSpot['distance'], 3, '.', '')*1000 . 'm </div>';
    echo '<div>Volných míst: ' . $parkingSpot['numOfFreePlaces'] . ' </div>';
    echo '<div><a href="https://maps.google.com/?q=' . $parkingSpot['lat'] . ',' . $parkingSpot['lng'] . '" target="_blank" rel="nofollow noopener noreferrer">Najít na mapě</a></div>';
    echo '</div>';
} ?>
    </div>
</body>
</html>