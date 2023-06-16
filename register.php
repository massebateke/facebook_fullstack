<?php
session_start();

require('config.php');

$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");

if($methode == "POST")
{
    $name = filter_input(INPUT_POST, "name");
    $first_name = filter_input(INPUT_POST, "first_name");
    $username = filter_input(INPUT_POST, "username");
    $email = filter_input(INPUT_POST, "email");
    $password = filter_input(INPUT_POST, "password");
    $age = filter_input(INPUT_POST, "age");
    $POST['genre'];
    $genre = $_POST['genre'];
    $num_tel = filter_input(INPUT_POST, "num_tel");
    $ecole = filter_input(INPUT_POST, "ecole");

    $requete = $pdo->prepare("
        INSERT INTO profil (name, first_name, username, email, password, age, genre, num_tel, ecole) VALUES(:name, :first_name, :username, :email, :password, :age, :genre, :num_tel, :ecole)
    ");
    $requete->execute([
        ":name" => $name,
        ":first_name" => $first_name,
        ":username" => $username,
        ":email" => $email,
        ":password" => password_hash($password, PASSWORD_DEFAULT),
        ":age" => $age,
        ":genre" => $genre,
        ":num_tel" => $num_tel,
        ":ecole" => $ecole,
    ]);

    header("Location: login.php");
    exit();
} 
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Inscription</title>
</head>
<body>
    <div class="formulaire_inscription">
      <form class="box" action="" method="POST">
          <div class="Intro">
              <div class="Titre">Bienvenue sur Unibook!</div>
              <div class="Titre">"Rejoignez la communauté Unibook pour vous connecter avec des étudiants du monde entier et élargir vos horizons académiques !"</div>
          </div>
          <div class="infos1">
              <div class="Titre" id="Inscrivez-vous">Inscrivez-vous maintenant</div>
              <div class="Nom">
                  <label>Nom <br>
                      <input type="text" class="box-input" name="name" value="" placeholder="Entrez votre nom" required>
                  </label>
              </div>
              <div class="Prénom">
                  <label>Prénom <br>
                      <input type="text" class="box-input" name="first_name" value="" placeholder="Entrez votre prénom" required>
                  </label>
              </div>
              <div class="Nom_dutilisateur">
                  <label>Nom d'utilisateur <br>
                      <input type="text" class="box-input" name="username" value="" placeholder="Entrez votre nom d'utilisateur" required>
                  </label>
              </div>
          </div>
          <div class="infos2">
              <div class="Email">
                  <label>Email <br>
                      <input type="email" class="box-input" name="email" value="" placeholder="Entrez votre email" required>
                  </label>
              </div>
              <div class="Mdp">
                  <label>Mot de passe <br>
                      <input type="password" class="box-input" name="password" value="" placeholder="Entrez votre mot de passe" required>
                  </label>    
              </div>
              <div class="Age">
                  <label>Âge <br>
                      <input type="text" class="box-input" name="age" value="" placeholder="Entrez votre âge" required>
                  </label>
              </div>
              <div class="Genre">
                  <label>Genre <br>
                      <input type="radio" class="radio-box"  id= "choixHomme" name="genre" value="Homme"> <label for="choixHomme">Homme</label>
                      <input type="radio" class="radio-box" id= "choixFemme" name="genre" value="Femme"> <label for="choixFemme">Femme</label>
                      <input type="radio" class="radio-box" id= "choixAutre" name="genre" value="Autre"> <label for="choixAutre">Autre</label>
                  </label>
              </div>
              <div class="Telephone">
                  <label>Téléphone <br>
                      <input type="tel" class="box-input" name="num_tel" value="" placeholder="Entrez votre numéro de téléphone" required>
                  </label>
              </div>
              <div class="Etablissement">
                  <label>Établissement <br>
                      <input type="text" class="box-input" name="ecole" value="" placeholder="Entrez le nom de votre établissement" required>
                  </label>
              </div>
              <input type="submit" name="submit" value="S'inscrire" class="button">
              <p class="box-register">Vous avez déjà un compte? <a href="login.php">Connectez-vous ici.</a></p>
          </div>
          <p id="footer">Contact | FAQ | Politique de confidentialité | Copyright Beta © 2023</p>
      </form>
    </div>
    
</body>
</html>