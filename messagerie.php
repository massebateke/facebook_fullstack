<?php
//ne pas oublier de vérifier token
require('config.php');
//require('recherche.php');
require('recherche.html');

session_start();
// ajouter une barre de recherche
$searchTerm = filter_input(INPUT_GET,"searchTerm");// Terme de recherche
$searchTerm = trim($searchTerm);

//$results = searchPeople($searchTerm);
$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if ($methode == "POST") {
    $nom_groupe = filter_input(INPUT_POST, "nom_groupe");
    $requete = $conn->prepare("INSERT INTO messagerie (name)  VALUES (?)");
    $requete->execute(array($nom_groupe));

    $requete2 = $conn->prepare("SELECT id FROM messagerie WHERE name = ?");
    $requete2->execute(array($nom_groupe));
    $groupe_id = $requete2->fetchAll(PDO::FETCH_ASSOC);
}

?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href= "messagerie.css">
    <title>Afficher tous les utilisateurs</title>

</head>

 <header class="header">
            <div class="container">
              <div class="Titre"><a href="fildactu.php">UNIBOOK</a></div>

                <form method="GET" action="profil.php" class="barrederecherche">
                    <input type="text" id="searchTerm1" name="searchTerm" placeholder="Rechercher quelqu'un sur Unibook">
                    <input type="submit" value="Rechercher" class="button-rechercher">
                </form>

                <nav class="header__nav">
                        <a href="messagerie.php"><img src="./images/message.png" class="message"></a>
                        <a href="logout.php"><img src="./images/logout.png" class="logout"></a>
                </nav>
            </div>
        </header>

<body>
    <form action="" method="post">
        <button id="creer_groupe_discussion" type="button" name="creer_groupe_discussion">+</button>
    </form>
    <form action = "creation_groupe_discussion.php?groupe_id=<? echo $groupe_id;?>" id="formulaire_nom_groupe" method="post" style="display: none;">
        <input placeholder="Nom du groupe" type="text" name="nom_groupe" id="nom_groupe" required>
        <input type="submit" name="submit" value="Créer" class="button" />

    </form>
    
    <!-- <h1>Recherche de personnes</h1>

    <form method="GET" action="recherche.php" class="search-bar">
        <label for="searchTerm">Nom ou prénom :</label>
        <input type="text" id="searchTerm" name="searchTerm" placeholder="Entrez un nom ou un prénom">
        <input type="submit" value="Rechercher">
    </form> -->
    <?php 
        /* $recupUsers = $conn->query("SELECT * FROM profil"); 
        while($user = $recupUsers->fetch() ){
            if($user['user_id'] != $_SESSION['user_id'])
            {
                ?> 
                <a href="message.php?id=<?php echo $user['user_id']?>">
                    <p><?php echo $user['username']; ?></p>
                </a>
                <?php
            }
        } */
    ?>
        <script>
        document.getElementById("creer_groupe_discussion").addEventListener("click", function() {

        var formulaire = document.getElementById("formulaire_nom_groupe");
        formulaire.style.display = "block";

        // Code supplémentaire à exécuter lorsque le bouton est cliqué

        
        });

    </script>
</body>
</html>