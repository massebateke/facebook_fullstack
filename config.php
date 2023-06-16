<?php
// Informations d'identification
$moteur = "mysql";
$host = 'localhost';
$port = '8889';
$dbname = 'syst_RS';
$user = 'Celena';
$password = 'Celena';

// Permet la connexion à la BDD
$dsn = "mysql:host=$host:$port;dbname=$dbname;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
}

?>