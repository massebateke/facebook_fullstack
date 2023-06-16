
<?php
session_start(); 

require('dbnam.php');

// Exécutez une requête SELECT pour récupérer les données de la page publique

$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if ($methode == "POST") {

    $requete = $pdo->prepare("
        INSERT INTO page (titre, contenu) VALUES (:title, :content);
    ");
    $requete->execute([
        ":title" => $_POST['titre'],
        ":content" => $_POST['contenu'],
    ]);
}

try {
    // Exécutez une requête SELECT pour récupérer les données de la page publique
    $sql = "SELECT * FROM page WHERE isPublic = 1"; // Assurez-vous d'adapter cette requête selon votre structure de table
    $result = $pdo->query($sql);             
    $pages = $result->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo 'Erreur lors de la récupération des pages publiques : ' . $e->getMessage();
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page Publique </title>
    <script src="script.js"></script>
</head>
<body>
 
    <h1>Création d'une page publique </h1>

    <form action="page.php" id="pageForm" method="post">
        <label for="titre">Titre : </label>
        <input type="text" name="titre" id="titre"><br><br>
        <label for="contenu">Contenu :</label><br><br>
        <textarea id="contenu" name="contenu"></textarea><br><br>
        <input type="submit" name="submit" value="Créer la page">
    </form>

    <form action="" method="POST" enctype="multipart/form-data">
        <input type="file" id="fichier" name="fichier"><br><br>
        <label for="fichier">Choisir un fichier :</label>
        <input type="submit" name="submit" value="Envoyer"><br><br>
    </form>

    <img src="chemin/vers/image.jpg" alt="Description de l'image">

    <div id="message"></div>
    
    <h2>Liste des pages publiques</h2>
    <ul id="pageList">
        <?php foreach ($pages as $page): ?>
            <li>
                <h3><?php echo $page['title']; ?></h3>
                <p><?php echo $page['contenu'];?></p>

            </li>
        <?php endforeach; ?>
    </ul>

    <div id="message"></div>
    
    <div id="message"></div>
    
<h2>Liste des pages publiques</h2>
<ul id="pageList">
    <?php foreach ($pages as $page): ?>
        <li>
            <h3><?php echo $page['titre']; ?></h3>
            <p><?php echo $page['contenu']; ?></p>
            <form action="edit_page.php" method="get">
                <input type="hidden" name="page_id" value="<?= $page['id']; ?>">
                <input type="submit" name="submit" value="Modifier">
            </form>
        </li>
    <?php endforeach; ?>
</ul>
</body>
</html>