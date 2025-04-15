<?php

session_start();

// DB CONNECTION
require __DIR__ . '/classes/Database.php';
use Classes\Database;
$db = new Database();

if($_POST["function"] == 'generateLink'){
    generateLink();
}

function generateLink(){

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    
        if($_SESSION['csrf_token'] !== $_POST['csrf_token']){

            die("Invalid csrf_token");

        }

        // Validate and sanitize input
        if (empty($_POST["urlInput"]) || !filter_var($_POST["urlInput"], FILTER_VALIDATE_URL)) {
            return null; // Invalid URL input
        }

        $baseUrl = trim($_POST["urlInput"]);
        $randomId = bin2hex(random_bytes(9));
        $shortUrl = 'www.pegagolpe.com.br/link.php?id=' . $randomId;

        $uniqueId = Database::select("SELECT id FROM generatedlinks WHERE id = ?", [$randomId]);

        while(count($uniqueId) > 0){
            $randomId = bin2hex(random_bytes(18));

            $uniqueId = Database::select("SELECT id FROM generatedlinks WHERE id = ?", [$randomId]);

        }

        $timestamp = time(); // Get current Unix timestamp
        $mysqlTimestamp = date('Y-m-d H:i:s', $timestamp); // Format it for MySQL

        $query = Database::insert("INSERT INTO generatedlinks (id, output_link, input_link, created_at) VALUES (?, ?, ?, ?)", [$randomId, $shortUrl, $baseUrl, $mysqlTimestamp]);

        header("Location: linkAnalysis.php?id=" . $randomId);

    }

}


?>