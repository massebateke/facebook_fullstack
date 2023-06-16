<?php
session_start();

require('config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $friendName = $_POST["friendName"];

    // Récupérer l'ID de l'utilisateur actuel
    if (isset($_SESSION["user_id"])) {
        $userId = $_SESSION["user_id"];

        // Rechercher l'ami dans la table "profil" en utilisant le nom ou le prénom
        $query = "SELECT * FROM profil WHERE first_name = ? OR name = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$friendName, $friendName]);

        $friendId = null;
        $friendExists = false;

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $friendId = $row["user_id"];
            $friendExists = true;
            break; // On ne récupère que le premier résultat
        }

        // Vérifier si l'ami a été trouvé
        if ($friendExists) {
            // Vérifier si l'amitié existe déjà dans la table "relation_amis"
            $query = "SELECT * FROM relation_amis WHERE (user_id = ? AND friend_id = ?) OR (user_id = ? AND friend_id = ?)";
            $stmt = $conn->prepare($query);
            $stmt->execute([$userId, $friendId, $friendId, $userId]);

            $friendshipExists = false;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $friendshipExists = true;
                break; // On ne vérifie que le premier résultat
            }

            // Ajouter l'amitié dans la table "relation_amis" si elle n'existe pas déjà
            if (!$friendshipExists) {
                $query = "INSERT INTO relation_amis (user_id, friend_id) VALUES (?, ?)";
                $stmt = $conn->prepare($query);
                $stmt->execute([$userId, $friendId]);

                echo "L'utilisateur a été ajouté en tant qu'ami avec succès !";
            } else {
                echo "L'utilisateur est déjà votre ami.";
            }
        } else {
            echo "Personne n'a été trouvé avec ce nom ou prénom.";
        }
    } else {
        echo "Erreur: L'ID de l'utilisateur n'est pas défini.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0"/>
<link rel="stylesheet" href="ajout.css">
    <title>Ajouter un ami</title>
    
</head>
<header class="header">
            <div class="container">
              <div class="header__logo">UNIBOOK</div>
              
                <form action="/recherche" method="get">
                <div class="recherche">
                  <input type="text" class="bar"name="query"  placeholder="Rechercher sur UNIBOOK">
                </div>
              </form>
              <nav class="header__nav">
                <ul>
                  <li><a href="#"><img src="./images/message.png" class="message"></a></li>
                  <li><a href="#"><img src="./images/logout.png" class="logout"></a></li>
                </ul>
              </nav>
            </div>
</header>
<body>
    <h1>Ajouter un ami:</h1>
    <form method="POST">
        <div class="coco">
        <label>Nom ou prénom de l'ami :
            <input type="text" name="friendName" class="friendName" required>
        </label>
        <button class="ajouter" type="submit">Ajouter</button>
    </div></form>
</body>
</html>
