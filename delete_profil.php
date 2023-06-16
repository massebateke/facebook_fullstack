<?php

session_start();

require('config.php');

$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if($methode == "POST")
{
    $username = $_COOKIE["username"];
    $requete = $conn->prepare("
        SELECT user_id FROM profil WHERE username = :username
    ");
    $requete->execute([
        ":username" => $username
    ]);
    $resultat = $requete->fetchAll();
    $user_id = $resultat['user_id'];

    $requete = $conn->prepare("
        DELETE FROM profil WHERE user_id = :user_id
    ");
    $requete->execute([
        ":user_id" => $user_id
    ]);

    exit();
} 

?>



<!DOCTYPE html>
<html>
<head>
<link rel="stylesheet" href="style1.css" />
</head>
<body>
<img src="images/wallpaper.jpeg" alt="">
<div class="form">
  <form class="box" action="" method="POST">
    <h1 class="box-title"></h1>
    <input type="submit" name="submit" value="Supprimer" class="button" />
  </form>
</div>
</body>
</html> 