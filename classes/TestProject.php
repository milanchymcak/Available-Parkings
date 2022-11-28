<?php
class TestProject
{

    /**
     * Curl external API by $url
     *
     * @return string|null
     */
    protected function curl(string $url):?string {
        try {
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_HTTPGET, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        } catch(Exception $e) {
            return '';
        }
    }

    /**
     * Gather All Parking Info & Save it as json
     *
     * @return array|null
     */
    public function createParkingDB():?array {

        // Check if already cached
        if(file_exists('parkingData.json')) {
            return json_decode(file_get_contents('parkingData.json'), true);
        }

        // Fetch
        $response = $this->curl('https://www.tsk-praha.cz/tskexport3/json/parkings');
        $responseArr = json_decode($response, true);

        if(!isset($responseArr['desc'])) return [];

        // Save to local cache
        file_put_contents('parkingData.json', $response);

        // Return
        return $responseArr;

    }

    /**
     * Get API Json
     *
     * @return array|null
     */
    protected function curlIpApi (string $ip = ''):?array {

        // Check if already cached
        if(file_exists('cache/ip/' . $ip . '.json')) {
            return unserialize(json_decode(file_get_contents('cache/ip/' . $ip . '.json'), true));
        }

        // Fetch
        $response = $this->curl('http://ip-api.com/php/' . $ip);

        // Unserialize
        $responseArr = unserialize($response);
        if(!isset($responseArr['status']) || $responseArr['status'] !== 'success') return [];

        // Save to local cache
        file_put_contents('cache/ip/' . $ip . '.json', json_encode($response));

        // Return
        return $responseArr;
    }

    /**
     * Get Visitor Real IP
     */
    public function getIP() {

        // if(isset($_SERVER['HTTP_CLIENT_IP'])) return $_SERVER['HTTP_CLIENT_IP'];
        // if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])) return $_SERVER['HTTP_X_FORWARDED_FOR'];
        // if(isset($_SERVER['HTTP_X_FORWARDED'])) return $_SERVER['HTTP_X_FORWARDED'];
        // if(isset($_SERVER['HTTP_FORWARDED_FOR'])) return $_SERVER['HTTP_FORWARDED_FOR'];
        // if(isset($_SERVER['HTTP_FORWARDED'])) return $_SERVER['HTTP_FORWARDED'];
        // if(isset($_SERVER['REMOTE_ADDR'])) return $_SERVER['REMOTE_ADDR'];

        // Fallback
        return '84.42.206.26'; // IP of the office
    }

    /**
     *
     */
    protected function getAddressByIp():array {
        /**
         * Get Visitor IP
         */
        $ip = $this->getIP();

        /**
         * Api call
         */
        return $this->curlIpApi($ip);
    }

    /**
     * Get Distance Between Two Points
     *
     * @return array
     */
    public function getDistanceBetweenPoints(string $targetLocation = ''):array {

        // Get IP Location (lat, long)
        $currentLocation = $this->getAddressByIp();
        if(!isset($currentLocation['lat']) || !isset($currentLocation['lon'])) return 'Err';

        // Stack
        $stack = [];

        // Check Parking Spots
        $parkinSpots = $this->createParkingDB();
        foreach($parkinSpots['results'] as $parkingSpot) {
            // Get distance
            $distance = $this->haversineFormulaTwoPoints(
                [$currentLocation['lat'], $currentLocation['lon']],
                [$parkingSpot['lat'], $parkingSpot['lng']]
            );
            $stack[] = array(
                'distance' => $distance,
                'name' => $parkingSpot['name'],
                'numOfFreePlaces' => $parkingSpot['numOfFreePlaces']
            );
        }

        // sort by the nearest parking spots
        usort($stack, function ($a, $b) { return $a['distance'] - $b['distance']; });

        return $stack;
    }

    /**
     * Haversine Formula
     */
    protected function haversineFormulaTwoPoints(array $point1, array $point2) {
        $radius = 6371;
        $point1Lat = $point1[0];
        $point2Lat = $point2[0];
        $deltaLat = deg2rad($point2Lat - $point1Lat);
        $point1Long =$point1[1];
        $point2Long =$point2[1];
        $deltaLong = deg2rad($point2Long - $point1Long);
        $a = sin($deltaLat/2) * sin($deltaLat/2) + cos(deg2rad($point1Lat)) * cos(deg2rad($point2Lat)) * sin($deltaLong/2) * sin($deltaLong/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        $distance = $radius * $c;
        return $distance;    // in km
    }
}