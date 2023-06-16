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
    $membreId = $_POST["membre_id"];
    $groupId = $_POST["groupId"]; //OK


    // Récupérer l'ID de l'utilisateur actuel
    if (isset($_SESSION["user_id"])) {
        $user_id = $_SESSION["user_id"];
        $query = "SELECT * FROM groupe_profil WHERE id_profil = ? AND id_groupe = ?";
        $stmt = $conn->prepare($query);
        $stmt->execute([$user_id, $groupId]);
        $ifadmin = $stmt->fetch(PDO::FETCH_ASSOC);
    

        if($ifadmin['etat'] == 'admin'){
                // Rechercher le membre dans la table "groupe_profil" en utilisant l'ID
            $query = "SELECT * FROM groupe_profil WHERE id_groupe = ? AND id_profil = ?";
            $stmt = $conn->prepare($query);
            $stmt->execute([$groupId, $membreId]);

            $friendId = null;
            $friendExists = false;

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $friendExists = true;
                break; // On ne récupère que le premier résultat
            }

            var_dump($friendExists);

            // Vérifier si l'ami a été trouvé
            if ($friendExists) {
                // Vérifier si le memebre existe dans la table "groupe_profil"
                $query = "SELECT * FROM groupe_profil WHERE (id_groupe = ? AND id_profil = ?)";
                $stmt = $conn->prepare($query);
                $stmt->execute([$groupId, $membreId]);

                $friendshipExists = false;

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    $friendshipExists = true;
                    break; // On ne vérifie que le premier résultat
            }

                // Supprimer le membre de la table "groupe_profil" si elle existe
                if ($friendshipExists) {
                    $query = "DELETE FROM groupe_profil WHERE (id_groupe = ? AND id_profil = ?)";
                    $stmt = $conn->prepare($query);
                    $stmt->execute([$groupId, $membreId]);

                    echo "L'utilisateur a été supprimé du groupe !";
                } else {
                    echo "L'utilisateur n'est pas dans le groupe.";
                }
            } else {
                echo "Personne n'a été trouvée avec ce nom ou prénom.";
            }
        }
    } else {
        echo "Erreur: L'ID de l'utilisateur n'est pas défini.";
    }
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Supprimer un membre d'un groupe</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Supprimer un membre</h1>
    <form action="" method="POST">
        <label for="membre">ID du membre :</label>
        <input type="text" name="membre_id" id="membre" required>

        <label for="groupId">ID du groupe : </label>
        <input type="text" name="groupId" id="groupId" required>
        <button type="submit">Supprimer</button>
    </form>
</body>
</html>
