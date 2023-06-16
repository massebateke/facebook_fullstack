<?php


require('config.php');

$methode = filter_input(INPUT_SERVER, "REQUEST_METHOD");
if ($methode == "POST") {
    $username = $_COOKIE["username"];

    $requete = $conn->prepare("SELECT etat FROM profil WHERE username = :username");
    $requete->execute(['username' => $username]);
    $resultat = $requete->fetchAll();
    $etat_profil = $resultat['etat'];


    if ($etat_profil == True){
        $requete = $conn->prepare("UPDATE profil SET etat = false WHERE username = :username");
        $requete->execute(['username' => $username]);
        $resultat = $requete->fetchAll();
        $activate_or_desactivate = "Activer le profil";
        
    }
    else{
        $requete = $conn->prepare("UPDATE profil SET etat = true WHERE username = :username");
        $requete->execute(['username' => $username]);
        $resultat = $requete->fetchAll();
        $activate_or_desactivate = "Désactiver le profil";
        
    }



}

$activate_or_desactivate = "yes bro";
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Activer ou désactiver</title>
</head>
<body>
<div class="form">
  <form class="box" action="" method="POST">
    <h1 class="box-title"></h1>
    <input type="submit" name="submit" value = "<?php echo $activate_or_desactivate; ?>" class="button" />
  </form>
    
</body>
</html>