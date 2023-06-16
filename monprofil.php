<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require("config.php");
require("profil-banniere.php");
//require("token.php");

$evenement = null;
session_start();
//if (isset($_COOKIE["validate"]) && $_COOKIE["validate"] == true) {
    $username = $_SESSION["username"];
    var_dump($username);

  $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
  
  if($methode == "POST")
  {
    if(isset($_POST['submit'])){
        // Récupère les données du formulaire
        $first_name = filter_input(INPUT_POST, "first_name");
        $name = filter_input(INPUT_POST, "name");
        $genre = filter_input(INPUT_POST, "genre");
        $age = filter_input(INPUT_POST, "age");
        $num_tel = filter_input(INPUT_POST, "num_tel");
        $ecole = filter_input(INPUT_POST, "ecole");


        // Vérifier que les champs ne sont pas vides
        if(empty($first_name) || empty($name) || empty($genre) || empty($age) || empty($num_tel) || empty($ecole)){
            echo "Tous les champs sont obligatoires.";
        } else {

            // Met à jour les données de l'événement dans la BDD

            $requete = $conn->prepare("
                    
                UPDATE profil SET first_name = :first_name, name = :name, genre = :genre, age = :age, num_tel = :num_tel, ecole = :ecole WHERE username=:user LIMIT 1
            ");
            
            //   $requete ->bindValue(':user',$username, PDO::PARAM_STR_CHAR);

            $requete->execute([
                ":first_name" => $first_name,
                ":name" => $name,
                ":genre" => $genre,
                ":age" => $age,
                ":num_tel" => $num_tel,
                ":ecole" => $ecole,
                ":user" => $username
            ]);

            // Affiche un message de confirmation
            
            echo "L'évènement \"$username\" a été modifié avec succès.";
        }
    }

    elseif(isset($_POST['supprimer'])){
        
        $user_id = $_SESSION["user_id"];
        var_dump($user_id);

        $requete = $conn->prepare("
            DELETE FROM profil WHERE user_id = :user_id
        ");
        $requete->execute([
            ":user_id" => $user_id
        ]);

        exit();
    }
    elseif(isset($_POST['desactiver'])){
        $activate_or_desactivate = "Désactiver le profil";
        $requete = $conn->prepare("SELECT etat FROM profil WHERE username = :username");
        $requete->execute(['username' => $username]);
        $resultat = $requete->fetchAll();
        $etat_profil = $resultat['etat'];


        if ($etat_profil == True){
            $requete = $conn->prepare("UPDATE profil SET etat = false WHERE username = :username");
            $requete->execute(['username' => $username]);
            $resultat = $requete->fetchAll();
            $activate_or_desactivate = "Activer le profil";
            $nouvel_etat = False;                
        }
        else{
            $requete = $conn->prepare("UPDATE profil SET etat = true WHERE username = :username");
            $requete->execute(['username' => $username]);
            $resultat = $requete->fetchAll();
            $activate_or_desactivate = "Désactiver le profil";
            $nouvel_etat = False;
        }
        $requete = $conn->prepare("UPDATE profil SET etat = :etat WHERE username = :username");
        $requete->execute(['etat' => $nouvel_etat]);
    
  } 
}

$requete = $conn ->prepare("SELECT * FROM profil WHERE username = :username");
// $requete ->bindParam(':username', $username, PDO::PARAM_STR_CHAR);
 $requete -> execute([
     ":username" =>$username
 ]);
//  global $evenement;
 $evenement = $requete->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link rel="stylesheet" href= "./CSS/profil.css">
  <title>Mon profil</title>

</head>
<body>
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

    <form class="form" id="form-banniere" action="profil-banniere.php" enctype="multipart/form-data" method="post" >
        <div class="uploadbanniere" >
            <img id = "banniere" src="img/<?php echo $image_banniere; ?>" title="<?php echo $image_banniere; ?>" alt=""><!-- Affiche l'image de bannière de l'utilisateur -->
            <div class="round">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"><!-- Champ caché contenant l'ID de l'utilisateur -->
                <input type="hidden" name="name" value="<?php echo $name; ?>"><!-- Champ caché contenant le nom de l'utilisateur -->
                <input type="file" name="photo_banniere" id="photo_banniere" accept=".jpg, .jpeg, .png"><!-- Champ de formulaire pour sélectionner une nouvelle image -->
                <i class="fa fa-camera" style="color: #fff"></i>
            </div>
        </div>
    </form>
    <form class="form" id="form-profil" action="profil-banniere.php" enctype="multipart/form-data" method="post">
        <div class="uploadprofilbanniere">
            <img id="profil" src="img/<?php echo $image_profil; ?>" width="125" height="125" title="<?php echo $image_profil; ?>" alt=""><!-- Affiche l'image de profil de l'utilisateur -->
            <div class="round">
                <input type="hidden" name="user_id" value="<?php echo $user_id; ?>"><!-- Champ caché contenant l'ID de l'utilisateur -->
                <input type="hidden" name="name" value="<?php echo $name; ?>"><!-- Champ caché contenant le nom de l'utilisateur -->
                <input type="file" name="photo_profil" id="photo_profil" accept=".jpg, .jpeg, .png"><!-- Champ de formulaire pour sélectionner une nouvelle image -->
                <i class="fa fa-camera" style="color: #fff"></i>
            </div>
        </div>
    </form>

    <script type="text/javascript">
           //Lorsque le champ d'upload "photo_profil" change, soumet le formulaire "form-profil"
            document.getElementById("photo_profil").onchange = function() {
                document.getElementById('form-profil').submit();
            }

            // Lorsque le champ d'upload "photo_banniere" change, soumet le formulaire "form-banniere"
            document.getElementById("photo_banniere").onchange = function() {
                document.getElementById('form-banniere').submit();
            }
    </script>

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

    <form method="POST">
        <div class="profil">
            
            <h1><?php echo $evenement['username']; ?></h1>

            <div class="infos">
            
                <div class="name">
                    <label>Nom:
                        <p class="box-input" name="name" ><?php echo $evenement['name']; ?></p>
                    </label>
                </div>

                    <label>Prénom: 
                        <p class="box-input" name="first_name"> <?php echo $evenement['first_name']; ?></p>
                    </label>
                    <br>

                    <label>Genre:
                            
                        <p class="box-input" name="genre"> <?php echo $evenement['genre']; ?> </p>
                    </label>
                    <br>
                        

                    <label>Age:
                        <p class="box-input" name="age"> <?php echo $evenement['age']; ?></p>
                    </label>
                    <br>
                        
                    <label>Numéro de téléphone:
                        <p class="box-input" name="num_tel" ><?php echo $evenement['num_tel']; ?></p>
                    </label>
                    <br>

                    <label>Ecole: 
                        <p class="box-input" name="ecole" ><?php echo $evenement ['ecole']; ?></p>
                    </label>
            </div>

        </div>
    </form>
  
</body>
</html>