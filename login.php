<?php
    session_start();
    
    require('config.php');
    
    $methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
    $error = null;
    
    if ($methode == "POST") {
      $login = filter_input(INPUT_POST, "login");
      $password = filter_input(INPUT_POST, "password");
      $_SESSION["username"]=$login;
    
      $requete = $conn->prepare("SELECT * FROM profil WHERE username = :login");
      $requete->execute([":login" => $login]);
    
      $user = $requete->fetch(PDO::FETCH_ASSOC);
      $_SESSION["user_id"]=$user['user_id'];
    
    
      if (password_verify($password, $user["password"])) {
    
        $_SESSION["loggedin"] = true;
        $error = null;
        
        $token = uniqid('', true);
        setcookie("token", $token);
    
        $requete1 = $conn->prepare("UPDATE profil SET token = :token WHERE username = :login");
        $requete1->execute([":token" => $token, ":login" => $login]);
    
        header('Location: accueil.php');
        exit();
      } else {
        $error = "Identifiants invalides";
      }
    }
    ?>

<!DOCTYPE html>
<html>
    <head>
        <link href="https://fonts.googleapis.com/css?family=Helvetica+Neue&display=swap" rel="stylesheet" />
        <link href="https://fonts.googleapis.com/css?family=Poppins&display=swap" rel="stylesheet" />
        <link href="./css/main.css" rel="stylesheet" />
        <title>Document</title>
    </head>
    <body>
        <div class="screen">

                <div class="carré-de-co">
                     <h1 class="Connexion">Connexion</h1>
                    <form action="" method="POST">
                      <label><h2 class="nom">Nom d'utilisateur </h2>
                          <input class="username" placeholder="Nom d'utilisateur" type="text" name="login" id="login">
                      </label>
                      <label><h2 class="mdp">Mot de passe</h2>
                          <input class="password" placeholder="Mot de passe" type="password" name="password" id="password">
                      </label>

                      <p class="oublié"><a href="#">Mot de passe oublié</a></p> 
                          <p class="séparation">|</p>
                    
                      <p class="inscription"><a href="#">Inscription</a></p>
                      <input type="submit" name="submit" value="Login" class="button" />

                    </form>
                </div>
                
            </div>
</body>
    </html>