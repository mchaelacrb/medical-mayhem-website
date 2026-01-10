<?php
$host = 'localhost';
$dbname = 'evd_db'; //palitan ng db (sa db nag add me ng another column for email authentication)
$user = 'root'; //eto lagay yung user 
$pass = ''; //tas pass ng domain

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
   
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

