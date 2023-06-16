<?php
require_once("config.php");

// Vérifier si les données de réaction et l'ID de la publication sont envoyés via la méthode POST
if(isset($_POST["contenureact"]) && isset($_POST["publication_id"])){
    $contenureact = $_POST["contenureact"];
    $publicationId = $_POST["publication_id"];
    $userid = 2; // L'ID de l'utilisateur, à remplacer par la valeur appropriée
    
    // Insérer la réaction dans la base de données
    $query = "INSERT INTO reaction (user_id, publication_id, contenureact) VALUES (?,?,?)";
    $sql = $conn->prepare($query);
    $sql->bind_param("iis", $userid, $publicationId, $contenureact);
    $sql->execute();
    $sql->close();

    // Récupérer le nombre total de likes pour la réaction spécifique et selon le commentaire specifique 
    $query = "SELECT * FROM reaction WHERE publication_id = ?";
    $sql = $conn->prepare($query);
    $sql->bind_param("i", $publicationId);
    $sql->execute();
    $result = $sql->get_result();
    $data = $result->fetch_all(MYSQLI_ASSOC);
    $sql->close();
    $countLike = count($data);
    echo $countLike;
}


