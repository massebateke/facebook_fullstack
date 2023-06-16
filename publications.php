<?php
//ne pas oublier de vérifier token
require('config.php');

session_start();

if(isset($_POST['publish'])){

    if(!empty($_POST['content'])){
        extract($_POST);
        $requete = $conn->prepare("INSERT INTO publication (contenu, user_id, date_creation) VALUES (:contenu, :user_id, NOW())");

        $requete->execute([
            ":contenu" => $_POST['content'],
            ":user_id" => $_SESSION['user_id'],
        ]);

        
        echo "Le statut à été posté avec succès.";
    }
    else{
        echo "Aucun contenu n'a été fourni"; 
    }
}

//redirect() vers  une page après 

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
    <title>Publications</title>
</head>
<body>
    <div class="col-md-6">
        <div class="statuts-post">
            <form action="" data-parsley-validate method="POST">
                <div class="form-group">
                    <label for="content">Statuts:</label>
                    <textarea name="content" id="content" rows="4" class="" placeholder="Quoi de neuf?" required></textarea>
                </div>

                <div class="form-group">
                    <input type="submit" name="publish" value="Publier" class="button">
                </div>
            </form>
        </div>

        <?php if(Count($publications) != 0): ?>
            <?php foreach ($publications as $publication) : ?>
                <article class="statut">
                    <div class="pull-left">
                        <img src="<?=$publication['image']?>" alt="">
                    </div>
                    <div class="media-body">
                        <h4 class="media-heading"><?$_SESSION['username']?></h4>
                        <p><i class="clock"></i><?=$publication['date_creation'] ?></p>
                        <?=$publication['contenu'] ?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</body>
</html>