<?php

require("config.php");
//require("token.php");

$evenement = null;
session_start();
//if (isset($_COOKIE["validate"]) && $_COOKIE["validate"] == true) {
    $username = $_SESSION["username"];

var_dump($evenement);

  $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
  if($methode == "POST")
  {
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
//}

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
  <title>Connexion</title>
  <link rel="stylesheet" type="" href="../assets/styles/style1.css">
  <style>

    h1 {
      margin-top: -32px;
    }
    button {
      margin-right: 10px;
    } 

    .form img {
      position: absolute;
      width: 40px;
      height: 40px;
    }
    .form form input {
      margin-bottom: 0px;
    }
  </style>
</head>
<body>
  <img src="../assets/images/wallpaper2.jpeg" alt="">
  <div class="form">
    <a href="direction.php" style="z-index: 999;"><img src="../assets/images/arrow1.png" alt="" class="arrow"></a> <!-- changer la direction pour le bouton -->
    <h1>Modifier le profil</h1>
    <form method="POST">
      <input placeholder="Prénom" type="text" name="first_name" value= "<?php echo $evenement['first_name']; ?>"><br><br>
      <input placeholder="Nom" type="text" name="name" value= "<?php echo $evenement['name']; ?>"><br><br>
      <input placeholder="Genre" type="text" name="genre" value= "<?php echo $evenement['genre']; ?>" ><br><br>
      <input placeholder="Age" type="text" name="age" value= "<?php echo $evenement['age']; ?>" ><br><br>
      <input placeholder="Numéro de téléphone" type="text" name="num_tel" value= "<?php echo $evenement['num_tel']; ?>" ><br><br>
      <input placeholder="Ecole" type="text" name="ecole" value= "<?php echo $evenement ['ecole']; ?>" ><br><br>
      <input class="submit" type="submit" name="submit" value="Modifier" class="box-button" />
    </form>
  </div>
</body>
</html>