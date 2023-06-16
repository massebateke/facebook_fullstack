<?php
//ne pas oublier de vérifier token
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');

session_start();

if(isset($_POST['publish'])){

    if(!empty($_POST['content'])){
        echo $_POST['content'];
        echo $_SESSION['user_id'];
        extract($_POST);

        $requete = $conn->prepare("INSERT INTO publication (contenu, user_id, date_creation) VALUES (:contenu, :user_id, NOW())");

        $requete->execute([
            ":contenu" => $_POST['content'],
            ":user_id" => $_SESSION['user_id'],
        ]);

        if ($requete->errorInfo()[0] !== '00000') {
            echo "Erreur lors de l'insertion en base de données : " . $requete->errorInfo()[2];
        } else {
            echo "Le statut a été posté avec succès.";
        }
    }
}

$requete = $conn->prepare(" SELECT profil.image, profil.username, publication.contenu, publication.date_creation FROM profil JOIN publication ON profil.user_id = publication.user_id WHERE profil.user_id = :user_id ORDER BY date_creation DESC ");

$requete->execute([
    ":user_id" => $_SESSION['user_id'],
]);

$publications = $requete->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./CSS/fildactu.css">
    <title>Fil d'actualité</title>
</head>
<body>
    <div class= "actualité">
        <header class="header">
            <div class="container">
              <div class="Titre"><a href="fildactu.php">UNIBOOK</a></div>

                <form method="GET" action="profil.php" class="barrederecherche">
                    <input type="text" id="searchTerm" name="searchTerm" placeholder="Rechercher quelqu'un sur Unibook">
                    <input type="submit" value="Rechercher" class="button-rechercher">
                </form>

                <nav class="header__nav">
                        <a href="messagerie.php"><img src="./images/message.png" class="message"></a>
                        <a href="logout.php"><img src="./images/logout.png" class="logout"></a>
                </nav>
            </div>
        </header>

        <div class="AGP">
              <h2><a href="monprofil.php">Mon profil</a></h2>
              <h3 class="titre-AGP">Amis</h3>
              <p><a href="listeamis.php">Afficher ma liste d'amis</a> </p>
              <hr>
              <h3 class="titre-AGP">Groupes</h3>
              <p><a href="#">Afficher mes groupes</a></p>
              <hr>
              <h3 class="titre-AGP">Pages</h3>
              <p><a href="#">Afficher mes pages</a></p>
        </div>

        <form action="" data-parsley-validate method="POST">
            <div class="form-group">
                <label for="content"></label>
                <textarea name="content" id="content" rows="4" class="" placeholder="Quoi de neuf?" required></textarea>
            </div>

            <div class="form-group">
                <input type="submit" name="publish" value="Publier" class="button-publier">
            </div>
        </form>

        <?php if(Count($publications) != 0): ?>            
            <?php foreach ($publications as $publication) : ?>
                <div class="pub-container">
                    <div class="pull-left">
                        <img src="<?=$publication['image']?>" alt="">
                    </div>
                    <h4 class="media-heading"><?$_SESSION['username']?></h4>
                    <p class="publié">Publié le <i class="clock"></i><?=$publication['date_creation'] ?></p>
                    <p class="contenu"><?=$publication['contenu'] ?></p>
                    <input type="submit" name="submit" value="J'aime" class="like-button">
                    <input type="submit" name="submit" value="Commentaire" class="comment-button">
                </div>
            <?php endforeach; ?>        
        <?php endif; ?>
    </div>
</body>
</html>
