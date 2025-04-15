<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/classes/Database.php';

use Classes\Database; 

$db = new Database();

$id = $_GET["id"];

date_default_timezone_get();
?>





<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Geolocation</title>
</head>
<body onload="getLocation()">
  <p id="demo">Getting location...</p>
  <p><span id="js-ua">Loading...</span></p>

  <script>
    const jsUA = navigator.userAgent;
    document.getElementById('js-ua').textContent = jsUA;
  </script>

  <script>
    const x = document.getElementById("demo");

    function getLocation() {
      if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(success, error);
      } else {
        x.innerHTML = "Geolocation is not supported by this browser.";
      }
    }

    function success(position) {
      x.innerHTML = "Latitude: " + position.coords.latitude +
      "<br>Longitude: " + position.coords.longitude;
    }

    function error(err) {
      x.innerHTML = "Erro ao obter localização: " + err.message;
    }
  </script>
</body>

</html>

        <?php
        if ($db->getConnection()) {
            
                $serverUA = $_SERVER['HTTP_USER_AGENT'];
                echo $serverUA .'<br><br><br>';


                    $dadosSite = Database::select("SELECT * FROM generatedlinks WHERE id = ?", [$id]);

                    foreach($dadosSite as $dado){
                    
                    $ip = get_client_ip();
                    $loc = file_get_contents("http://ip-api.com/json/". $ip);
                    

                   // $browser = get_browser(null, true);

                    //$loc = file_get_contents("http://ip-api.com/json/$ip");

                    $locdecode = json_decode($loc);

                    date_default_timezone_set($locdecode->timezone); 

                        
                    echo $loc;

                    $insertSite =
                    Database::insert("INSERT INTO linkaccess (access_ip, access_state, access_city, access_datetime, access_country, access_provider, access_timezone, access_lon, access_lat, link_id) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", 
                                      [$ip, $locdecode->regionName, $locdecode->city, date('Y-m-d H:i:s', time()), $locdecode->country, $locdecode->isp, $locdecode->timezone, $locdecode->lon, $locdecode->lat, $id]);

                    //die();
                    header('location: '.$dado["input_link"].'');

                    exit;
                    }
    
                } else {
                    echo "Falha na conexão!";
                }

        ?>



<?php


function get_client_ip() {
    $ipaddress = '';
    if (isset($_SERVER['HTTP_CLIENT_IP']))
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    else if(isset($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_X_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    else if(isset($_SERVER['HTTP_FORWARDED_FOR']))
        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    else if(isset($_SERVER['HTTP_FORWARDED']))
        $ipaddress = $_SERVER['HTTP_FORWARDED'];
    else if(isset($_SERVER['REMOTE_ADDR']))
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}


?>