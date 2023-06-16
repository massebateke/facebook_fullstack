<?php 
function searchPeople($searchTerm) {
    global $conn; // Utilisez la connexion à la base de données globale

    $results = array(); // Tableau pour stocker les résultats

    // Préparation de la requête SQL avec des paramètres préparés
    $stmt = $conn->prepare('SELECT * FROM Profil WHERE name LIKE ? OR first_name LIKE ?');
    
    // Validation et échappement des données entrées par l'utilisateur
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bindParam(1, $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(2, $searchTerm, PDO::PARAM_STR);

    // Exécution de la requête préparée
    $stmt->execute();

    // Récupération des résultats de la requête
    

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}
?>