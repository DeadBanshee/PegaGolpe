<?php

session_start();

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require __DIR__ . '/../classes/Database.php';

use Classes\Database; 

$db = new Database();

$id = $_GET["id"];

?>

<!DOCTYPE html>
<html lang="pt-BR">

        
        <?php
        if ($db->getConnection()) {
                    
                    $dadosSite = Database::select("SELECT * FROM generatedlinks WHERE id = ?", [$id]);



                    foreach($dadosSite as $dado){
                    header('location: '.$dado["input_link"].'');


                    
                    $ip = get_client_ip();
                    $insertSite = Database::insert("INSERT INTO linkaccess (access_ip) VALUES (?);", [$ip]);


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