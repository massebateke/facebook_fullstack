<?php
require('dbnam.php');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération des données du formulaire
    $pageTitle = $_POST["titre"];
    $pageContent = $_POST["contenu"];
    
    // Validation des données (à adapter selon tes besoins)
    if (empty($pageTitle) || empty($pageContent)) {
        $response = array("success" => false, "message" => "Veuillez remplir tous les champs.");
    } else {
        try {
            // Préparation de la requête SQL avec des paramètres
            $stmt = $pdo->prepare("INSERT INTO page (name, contenu) VALUES (?, ?)");
            
            // Exécution de la requête avec les valeurs fournies
            $stmt->execute([$pageTitle, $pageContent]);

            $response = array("success" => true, "message" => "La page a été créée avec succès !");
            
            // Redirection vers la page "fichier.php"
            header("Location: fichier.php");
            exit();
        } catch (PDOException $e) {
            $response = array("success" => false, "message" => "Erreur lors de la création de la page : " . $e->getMessage());
        }
    }
    
    // Envoi de la réponse JSON
    header("Content-Type: application/json");
    echo json_encode($response);
    exit();
} else {
    try {
        // Exécutez une requête SELECT pour récupérer les données de la page publique
        $sql = "SELECT * FROM page WHERE isPublic = 1"; // Assurez-vous d'adapter cette requête selon votre structure de table
        $result = $pdo->query($sql);
        $pages = $result->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur lors de la récupération des pages publiques : ' . $e->getMessage();
        die();
    }

    // Afficher les données récupérées
    foreach ($pages as $page) {
        echo "<h2>" . $page['name'] . "</h2>";
        echo "<p>" . $page['contenu'] . "</p>";
    }

    // Fermeture de la connexion à la base de données
    $pdo = null;
}
?>

