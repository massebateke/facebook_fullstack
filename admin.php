<?php
require('dbnam.php');

try {
    // Connexion à la base de données
    $pdo = new PDO("mysql:host=localhost;dbname=syst_RS;charset=utf8", "Bichara", "Bichara");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Exécution de la requête pour récupérer la liste des administrateurs
    $sql = "SELECT * FROM administrateurs";
    $stmt = $pdo->query($sql);

    // Vérification si la requête a réussi
    if ($stmt) {
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Affichage de la liste des administrateurs
        foreach ($admins as $admin) {
            echo "<li>" . $admin['id'] . " - " . $admin['nom'] . "</li>";
        }
    } else {
        echo "Erreur lors de l'exécution de la requête.";
    }

    // Ajouter un nouvel administrateur
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_admin"])) {
        $adminName = $_POST["admin_name"];

        try {
            // Vérifier si l'administrateur existe déjà dans la table "admins"
            $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE nom = ?");
            $stmt->execute([$adminName]);
            $existingAdmin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($existingAdmin) {
                echo "Cet membre existe déjà.";
            } else {
                // Ajouter le nouvel administrateur dans la table "admins"
                $stmt = $pdo->prepare("INSERT INTO administrateurs (nom) VALUES (?)");
                $stmt->execute([$adminName]);

                echo "Le nouvel membre a été ajouté avec succès !";
            }
        } catch (PDOException $e) {
            echo "Erreur lors de l'ajout de l'membre : " . $e->getMessage();
        }
    }

    // Supprimer un administrateur existant
    if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["delete_admin"])) {
        $adminId = $_POST["admin_id"];

        try {
            // Vérifier si l'administrateur existe dans la table "admins"
            $stmt = $pdo->prepare("SELECT * FROM administrateurs WHERE id = ?");
            $stmt->execute([$adminId]);
            $admin = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($admin) {
                // Supprimer l'administrateur de la table "admins"
                $stmt = $pdo->prepare("DELETE FROM administrateurs WHERE id = ?");
                $stmt->execute([$adminId]);

                echo "membre a été supprimé avec succès !";
            } else {
                echo "membre introuvable.";
            }
        } catch (PDOException $e) {
            echo "Erreur lors de la suppression de l'administrateur : " . $e->getMessage();
        }
    }

    // Récupérer la liste des administrateurs existants
     $stmt = $pdo->query("SELECT * FROM administrateurs");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Fermeture de la connexion à la base de données
    $pdo = null;
} catch (PDOException $e) {
    echo "Erreur lors de la connexion à la base de données : " . $e->getMessage();
    die();
}
?>


<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestion des membre</title>
</head>
<body>
    <h1>Gestion des membre</h1>
    
    <h2>Ajouter un membre</h2>
    <form action="" method="post">
        <label for="admin_name">Nom de membre :</label>
        <input type="text" name="admin_name" id="admin_name" required>
        <input type="submit" name="add_admin" value="Ajouter">
    </form>
    
    <h2>Liste des membre</h2>
    <ul>
        <?php foreach ($admins as $admin): ?>
            <li>
                <span>ID : <?php echo $admin['id']; ?></span>
                <span>Nom : <?php echo $admin['name']; ?></span>
                <form action="" method="post">
                    <input type="hidden" name="admin_id" value="<?php echo $admin['id']; ?>">
                    <input type="submit" name="delete_admin" value="Supprimer">
                </form>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
