<?php
session_start();

require('config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

// Fonction pour s'inscrire à un groupe public
function joinPublicGroup($groupId, $userId)
{
    global $conn;

    // Vérifier si l'utilisateur est déjà inscrit au groupe
    $checkQuery = "SELECT * FROM groupe WHERE id = ? AND type = 'public'";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$groupId]);

    if ($stmt->rowCount() === 0) { 
        return "Le groupe n'existe pas ou n'est pas public.";
    }

    // Vérifier si l'utilisateur est déjà inscrit dans groupe_profil
    $checkQuery = "SELECT * FROM groupe_profil WHERE id_groupe = ? AND id_profil = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$groupId, $userId]);

    if ($stmt->rowCount() > 0) { 
        return "Vous êtes déjà inscrit dans ce groupe.";
    }

    // Insérer l'utilisateur dans la table groupe_profil
    $insertQuery = "INSERT INTO groupe_profil (id_groupe, id_profil, etat) VALUES (?, ?, 'accepte')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->execute([$groupId, $userId]);

    return "Inscription réussie au groupe public.";
}

// Fonction pour postuler à un groupe privé
function applyPrivateGroup($groupId, $userId)
{
    global $conn;

    // Vérifier si l'utilisateur a déjà postulé au groupe
    $checkQuery = "SELECT * FROM groupe WHERE id = ? AND type = 'prive'";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$groupId]);

    if ($stmt->rowCount() === 0) { 
        return "Le groupe n'existe pas ou n'est pas privé.";
    }

    // Vérifier si l'utilisateur a déjà postulé dans groupe_profil
    $checkQuery = "SELECT * FROM groupe_profil WHERE id_groupe = ? AND id_profil = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$groupId, $userId]);

    if ($stmt->rowCount() > 0) { 
        return "Vous avez déjà postulé à ce groupe.";
    }

    // Insérer la demande de l'utilisateur dans la table groupe_profil
    $insertQuery = "INSERT INTO groupe_profil (id_groupe, id_profil, etat) VALUES (?, ?, 'attente')";
    $stmt = $conn->prepare($insertQuery);
    $stmt->execute([$groupId, $userId]);

    return "Votre demande a été envoyée au groupe privé. Attendez la confirmation.";
}

// Fonction pour accepter une demande pour rejoindre un groupe privé
function acceptGroupRequest($groupId, $userId)
{
    global $conn;

    // Vérifier si l'utilisateur est un administrateur du groupe
    $checkQuery = "SELECT * FROM groupe_profil WHERE id_groupe = ? AND id_profil = ? AND etat = 'accepte'";
    $stmt = $conn->prepare($checkQuery);
   
    $stmt->execute([$groupId, $userId]);

    if ($stmt->rowCount() === 0) { 
        return "Vous n'êtes pas autorisé à accepter les demandes pour rejoindre ce groupe.";
    }

    // Mettre à jour l'état de la demande de l'utilisateur dans la table groupe_profil
    $updateQuery = "UPDATE groupe_profil SET etat = 'accepte' WHERE id_groupe = ? AND id_profil = ?";
    $stmt = $conn->prepare($updateQuery);
    $stmt->execute([$groupId, $userId]);

    return "Demande acceptée pour rejoindre le groupe privé.";
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $groupId = $_POST["groupId"];
    $userId = $_SESSION["user_id"];

    $groupType = $_POST["groupType"];
    $result = "";

    if ($groupType === "public") {
        $result = joinPublicGroup($groupId, $userId);
    } elseif ($groupType === "prive") {
        $result = applyPrivateGroup($groupId, $userId);
    }
}



// Récupérer la liste des groupes
$selectQuery = "SELECT * FROM groupe";
$stmt = $conn->prepare($selectQuery);
$stmt->execute();
$groups = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Page d'inscription aux groupes</title>
</head>
<body>
    <?php echo $result ?> 
    <h1>Inscription aux groupes</h1>
        <form action="" method="POST">
           <label for="groupId">ID du groupe :</label>
           <input type="text" name="groupId" id="groupId" required>
   
           <label for="groupType">Type de groupe :</label>
           <select name="groupType" id="groupType" required>
               <option value="public">Public</option>
               <option value="prive">Privé</option>
           </select>
   
           <input type="submit" value="Rejoindre">
       </form>
</body>
</html>
