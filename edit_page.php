<?php
session_start(); 

require('dbnam.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les valeurs soumises du formulaire de modification
    $pageId = $_POST['page_id'];
    $nouveauTitre = $_POST['nouveau_titre'];
    $nouveauContenu = $_POST['nouveau_contenu'];

    // Effectuer la requête SQL de mise à jour
    $requete = $pdo->prepare("
        UPDATE page
        SET title = :titre, contenu = :contenu
        WHERE id = :id
    ");
    $requete->execute([
        ":titre" => $nouveauTitre,
        ":contenu" => $nouveauContenu,
        ":id" => $pageId
    ]);


    // Rediriger vers la page publique
    header('Location: index.php');
    exit();
} else {
    // Récupérer l'identifiant de la page à partir de l'URL
    //$pageId = $_GET['page_id'];

    // Effectuer une requête SQL pour récupérer les informations de la page
    $requete = $pdo->prepare("SELECT * FROM page WHERE id = :id");
    $requete->execute([":id" => $pageId]);
    $page = $requete->fetch(PDO::FETCH_ASSOC);


}

?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la page</title>
</head>
<body>
    <h1>Modifier la page</h1>


    <form action="edit_page.php" method="POST">
        <input type="hidden" name="page_id" value="<?php echo $_GET['page_id']; ?>">
        <label for="nouveau_titre">Nouveau titre :</label>
        <input type="text" name="nouveau_titre" id="nouveau_titre" value=""><br><br>
        <label for="nouveau_contenu">Nouveau contenu :</label><br><br>
        <textarea id="nouveau_contenu" name="nouveau_contenu"></textarea><br><br>
        <input type="submit" name="submit" value="Modifier">
    </form>
</body>
</html>
