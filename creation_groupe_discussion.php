<?php
//ne pas oublier de vérifier token
require('config.php');
require('fonctions.php');
//require('recherche.php');

session_start();


$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if($methode == "POST")
{
   
    // ajouter une barre de recherche
    $searchTerm = filter_input(INPUT_POST,"searchTerm");// Terme de recherche
    $searchTerm = trim($searchTerm);
    $results = searchPeople($searchTerm);

    if (isset($_POST['rechercher'])){
        // Affichage des résultats
        foreach ($results as $result) {
            $user_id = $result['user_id'];
            $username = $result['username'];
            
            //echo htmlspecialchars($result["username"]);
            echo "<a href='ajouter_membre_groupe.php?id=" .$user_id . "'>". $username ."</a>";
        
        }
    }
}


//$results = searchPeople($searchTerm);



//comment mettre le nom et l'image, on créer un groupe et après un change l'image et le nom?
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Création groupe discussion</title>
    <style>
        /* Styles CSS pour la barre de recherche */
        .search-bar {
            display: flex;
            align-items: center;
        }

        .search-bar input[type="text"] {
            width: 300px;
            padding: 8px;
            font-size: 16px;
        }

        .search-bar input[type="submit"] {
            padding: 8px 16px;
            font-size: 16px;
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
    </style>

</head>
<body>
    <h1>Recherche de personnes</h1>

    <form method="POST" action="" class="search-bar">
        <label for="searchTerm">Nom ou prénom :</label>
        <input type="text" id="searchTerm" name="searchTerm" placeholder="Entrez un nom ou un prénom">
        <input type="submit" name = "rechercher" value="Rechercher">
    </form>
    
</body>
</html>