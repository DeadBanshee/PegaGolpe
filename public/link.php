<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/../classes/Database.php';

use Classes\Database; 

$db = new Database();

$id = $_GET["id"];

date_default_timezone_get();
?>

<!DOCTYPE html>
<html lang="pt-BR">

        
        <?php
        if ($db->getConnection()) {
                    
                    $dadosSite = Database::select("SELECT * FROM generatedlinks WHERE id = ?", [$id]);



                    foreach($dadosSite as $dado){


                    
                    $ip = get_client_ip();
                    $loc = file_get_contents("http://ip-api.com/json/201.94.195.254");
                    

                   // $browser = get_browser(null, true);

                    //$loc = file_get_contents("http://ip-api.com/json/$ip");

                    $locdecode = json_decode($loc);

                    date_default_timezone_set($locdecode->timezone); 

                        
                    echo $loc;

                    $insertSite =
                    Database::insert("INSERT INTO linkaccess (access_ip, access_state, access_city, access_datetime, access_country, access_provider, access_timezone, access_lon, access_lat, link_id) 
                                      VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?);", 
                                      ['201.94.195.254', $locdecode->regionName, $locdecode->city, date('Y-m-d H:i:s', time()), $locdecode->country, $locdecode->isp, $locdecode->timezone, $locdecode->lon, $locdecode->lat, $id]);

//                    die();
                    header('location: '.$dado["input_link"].'');

                    exit;
                    }
    
                } else {
                    echo "Falha na conexão!";
                }

        ?>



</body>
</html>

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