<?php
session_start();

require('config.php');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true) {
    header("Location: login.php");
    exit();
}

// Fonction pour créer un groupe
function createGroup($groupName, $groupType)
{
    global $conn;

    // Vérifier si le groupe existe déjà
    $checkQuery = "SELECT * FROM groupe WHERE name = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->execute([$groupName]);

    if ($stmt->rowCount() > 0) { 
        return "Le groupe '$groupName' existe déjà.";
    }

    // Insérer le groupe dans la table 'groupe'
    $insertQuery = "INSERT INTO groupe (type, name) VALUES (?, ?)";
    $stmt = $conn->prepare($insertQuery);
    $stmt->execute([$groupType, $groupName]);

    return "Le groupe '$groupName' a été créé avec succès.";
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $groupName = $_POST["groupName"];
    $groupType = $_POST["groupType"];

    $result = createGroup($groupName, $groupType);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Création d'un groupe</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Création d'un groupe</h1>

    <?php
    // Afficher le résultat de la création du groupe
    if (isset($result)) {
        echo "<p>$result</p>";
    }
    ?>

    <form method="POST">
        <label for="groupName">Nom du groupe :</label>
        <input type="text" name="groupName" id="groupName" required><br><br>

        <label for="groupType">Type du groupe :</label>
        <select name="groupType" id="groupType" required>
            <option value="public">Public</option>
            <option value="prive">Privé</option>
        </select><br><br>

        <input type="submit" value="Créer le groupe">
    </form>
</body>
</html
