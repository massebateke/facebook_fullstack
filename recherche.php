<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');
require('fonctions.php');

$searchTerm = filter_input(INPUT_GET,"searchTerm");// Terme de recherche
$searchTerm = trim($searchTerm);

$results = searchPeople($searchTerm);



// Affichage des résultats
foreach ($results as $result) {
    $user_id = $result['user_id'];
    $username = $result['username'];
    
    //echo htmlspecialchars($result["username"]);
    echo "<a href='message.php?id=" .$user_id . "'>". $username ."</a>";
}

// Fermeture de la connexion à la base de données
$conn = null;
?>
