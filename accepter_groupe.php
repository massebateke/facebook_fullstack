<?php
session_start();

require('config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

// Fonction pour mettre à jour l'état d'un membre dans groupe_profil
function updateMemberStatus($userId, $groupId, $status) {
    global $pdo;
    
    // Vérifier si le membre existe dans le groupe
    $checkQuery = "SELECT * FROM groupe_profil WHERE id_profil = ? AND id_groupe = ?";
    $stmt = $pdo->prepare($checkQuery);
    $stmt->execute([$userId, $groupId]);

    if ($stmt->rowCount() === 0) { 
        return "Le membre n'est pas dans le groupe ou le groupe n'existe pas. ";
    }

    // Mettre à jour l'état du membre dans groupe_profil
    $updateQuery = "UPDATE groupe_profil SET etat = ? WHERE id_profil = ? AND id_groupe = ?";
    $stmt = $pdo->prepare($updateQuery);
    $stmt->execute([$status, $userId, $groupId]);

    return "L'état du membre a été mis à jour avec succès.";
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérer les valeurs du formulaire
    $userId = $_POST["memberId"];
    $groupId = $_POST["groupId"];
    $status = $_POST["status"];
    
    $result = updateMemberStatus($userId, $groupId, $status);

    if ($result === "L'état du membre a été mis à jour avec succès.") {
        // Faire quelque chose après la mise à jour réussie
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="accepter_groupe.css">
    <title>Changer l'état d'un membre</title>
</head>
<body>


<header class="header">
            <div class="container">
              <div class="Titre"><a href="fildactu.php">UNIBOOK</a></div>

                <form method="GET" action="profil.php" class="barrederecherche">
                    <input type="text" id="searchTerm" name="searchTerm" placeholder="Rechercher quelqu'un sur Unibook">
                    <input type="submit" value="Rechercher" class="button-rechercher">
                </form>

                <nav class="header__nav">
                        <a href="messagerie.php"><img src="./images/message.png" class="message"></a>
                        <a href="logout.php"><img src="./images/logout.png" class="logout"></a>
                </nav>
            </div>
        </header>
    <?php echo $result ?>
    <h1>Changer l'état d'un membre</h1>
    
   
    <form action="" method="post">
        <div class="caca">
            <label for="memberId">ID du membre :</label>
            <input type="text" name="memberId" id="memberId" required>
        </div>
        <br>
        <br>

        <div class="vvs">
            <label for="groupId">ID du groupe :</label>
            <input type="text" name="groupId" id="groupId" required>
            
            <label for="status">État :</label>
            <select name="status" class="status" required>
                <option value="Accepté">Accepté</option>
                <option value="Refusé">Refusé</option>
                <option value="Admin">Admin</option>
            </select>
            <input type="submit" value="Changer l'état" id="Changer">
        </div>
    </form>
   
</body>
</html>
