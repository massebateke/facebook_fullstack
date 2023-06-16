<?php
require("config.php");
session_start();

$id_groupe = null;
$group = null;

// Vérifier si l'utilisateur est admis au groupe
function isUserAdmitted($id_groupe, $id_profil)
{
    global $conn;

    $requete = $conn->prepare("SELECT * FROM groupe_profil WHERE id_groupe = :id_groupe AND id_profil = :id_profil AND etat = 'admis' LIMIT 1");
    $requete->execute([
        ":id_groupe" => $id_groupe,
        ":id_profil" => $id_profil
    ]);

    return $requete->rowCount() > 0;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupère les données du formulaire
    $id_groupe = filter_input(INPUT_POST, "id_groupe");
    $name = filter_input(INPUT_POST, "name");
    $type = filter_input(INPUT_POST, "type");

    // Vérifie que les champs ne sont pas vides
    if (empty($id_groupe) || empty($name) || empty($type)) {
        echo "Tous les champs sont obligatoires.";
    } else {
        // Vérifie si l'utilisateur est admis au groupe
        $id_profil = $_SESSION["id_profil"];
        if (isUserAdmitted($id_groupe, $id_profil)) {
            // Met à jour les données du groupe dans la BDD
            $requete = $conn->prepare("
                UPDATE groupe SET name = :name, type = :type WHERE id = :id_groupe LIMIT 1
            ");

            $requete->execute([
                ":name" => $name,
                ":type" => $type,
                ":id_groupe" => $id_groupe
            ]);

            // Affiche un message de confirmation
            echo "Le groupe a été modifié avec succès.";
        } else {
            echo "Vous n'êtes pas autorisé à modifier ce groupe.";
        }
    }
}

$groupes = array();

// Récupère la liste des groupes depuis la base de données
$requete = $conn->prepare("SELECT * FROM groupe");
$requete->execute();
$groupes = $requete->fetchAll(PDO::FETCH_ASSOC);
?>


<!DOCTYPE html>
<html>
<head>
    <title>Modification d'un groupe</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
    <h1>Modification d'un groupe</h1>

    <form method="POST">
        <label for="groupSelect">Sélectionnez le groupe :</label>
        <select name="id_groupe" id="groupSelect" required>
            <?php foreach ($groupes as $groupe): ?>
                <option value="<?php echo $groupe['id']; ?>">
                    <?php echo htmlspecialchars($groupe['name']); ?>
                </option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="name">Nouveau nom du groupe :</label>
        <input type="text" name="name" id="name" required><br><br>

        <label for="type">Nouveau type du groupe :</label>
        <select name="type" id="type" required>
            <option value="public">Public</option>
            <option value="prive">Privé</option>
        </select><br><br>

        <input type="submit" value="Modifier le groupe">
    </form>
</body>
</html>
