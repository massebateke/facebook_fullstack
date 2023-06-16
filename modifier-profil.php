<?php


require("config.php");
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
  <title>Connexion</title>
  <style>
     @import url('https://fonts.cdnfonts.com/css/helvetica-neue-5');
    *{
        color: white;
        font-family: 'Helvetica Neue';
    }
    body{
    background: url(./images/v20_106.png);
    
    }

    h1 {
        position: relative;
      margin-top: -32px;
      left: 150px;
    }
    button {
      margin-right: 10px;
    } 
    .profil {
    position: absolute;
    top: 150px;
    left: 600px;  
    padding-top: 30px;
    }   

    .header {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 60px;
  background-color: #292727;
  color: white;
  z-index: 999;
  }

.container {
  max-width: 1200px;
  margin: 0 auto;
  display: flex;
  justify-content: space-between;
  align-items: center;
  height: 100%;
}

.header__logo {
  font-family: 'Helvetica Neue';

  font-style: normal;
  font-size: 20px;
  font-weight: bold;
  margin-right: 20px;
}

.header__nav ul {
  display: flex;
  list-style: none;
  margin: 0;
  padding: 0;
}

.header__nav li {
  margin-right: 40px;
}

.header__nav li:last-child {
  margin-right: 0;
}

.header__nav a {
  color: white;
  text-decoration: none;
}

.recherche {

position: absolute;
width: 532px;
height: 30px;
left: 454px;
top: 13px;

}

.bar{
  position: absolute;
  width: 532px;
  height: 30px;
  left: 0px;
  top: 0px;
  color: #ffff;
  background: #292727;
  

  background-repeat: no-repeat;
  border-radius: 20px;
}

img{
  width: 30px;
  height: 30px;
  top: 0px;
  left: 0px;
}

.logout{

  width: 20px;
  height: 20px;
  
}

.message{

  width: 20px;
  height: 20px;

}
h1{
  color: #ffff;
}

.submit {
    color: black; 
  position: relative;
  height: 30px;
  width: 100px;
  left: 250px;
 font-size: 20px;
 border: 3px solid #B5D9FD;
 border-radius: 8px;
}
.submit:hover {
    color: black; 
background-color: #B5D9FD;
  border: 3px solid #ffff;
  border-radius: 8px;
}
.box-input{
  background: rgba(198, 202, 206, 0.9);
  border: 3px solid #B5D9FD;
  border-radius: 8px;
  height: 20px;
}
.button-desactiver {
    color: black; 
    position: absolute;
    width: 300px;
    height: 347px;
    top: 70px;
    left: 17px;
    font-family: 'Helvetica Neue';
    font-style: normal;
    
}
.Désactiver {
    color: black; 
 font-size: 20px;
 border: 3px solid #B5D9FD;
 border-radius: 8px;
}

.Désactiver:hover{
    color: black; 
  background-color: #B5D9FD;
  border: 3px solid #ffff;
  border-radius: 8px;
}

label{
    font-size: 20px
}


 .button-supp {
    color: black; 
    position: absolute;
    width: 300px;
    height: 347px;
    top: 130px;
    left: 17px;
    font-family: 'Helvetica Neue';
    font-style: normal;
}
.supprimer {
    color: black; 
 font-size: 20px;
 border: 3px solid #B5D9FD;
 border-radius: 8px;
}

.supprimer:hover{
    color: black; 
  background-color: #B5D9FD;
  border: 3px solid #ffff;
  border-radius: 8px;
}
  </style>
</head>
<body>
<header class="header">
            <div class="container">
              <div class="header__logo">UNIBOOK</div>
              
                <form action="/recherche" method="get">
                <div class="recherche">
                  <input type="text" class="bar"name="query"  placeholder="Rechercher sur UNIBOOK">
                </div>
              </form>
              <nav class="header__nav">
                <ul>
                  <li><a href="#"><img src="./images/message.png" class="message"></a></li>
                  <li><a href="#"><img src="./images/logout.png" class="logout"></a></li>
                </ul>
              </nav>
            </div>
          </header>
  <img src="../assets/images/wallpaper2.jpeg" alt="">
    <a href="direction.php" style="z-index: 999;"><img src="../assets/images/arrow1.png" alt="" class="arrow"></a> <!-- changer la direction pour le bouton -->
    
    <form method="POST">
        <div class="profil">
            
      <h1><?php echo $evenement['username']; ?></h1>
      <label>Prénom: 
      <input placeholder="Prénom" class="box-input"type="text" name="first_name" value= "<?php echo $evenement['first_name']; ?>">
      </label>

      <br>
      <br>

      <label>Nom:
      <input placeholder="Nom" class="box-input" type="text" name="name" value= "<?php echo $evenement['name']; ?>">
        </label>

        <br>
        <br>

        <label>Genre:<br>
            <input type="radio" class="radio-box"  id= "choixHomme" name="genre" value="Homme"> <label for="choixHomme">Homme</label>
            <input type="radio" class="radio-box" id= "choixFemme" name="genre" value="Femme"> <label for="choixFemme">Femme</label>
            <input type="radio" class="radio-box" id= "choixAutre" name="genre" value="Autre"> <label for="choixAutre">Autre</label>
            <input placeholder="Genre" type="text" name="genre" value= "<?php echo $evenement['genre']; ?>" >
        </label>

        <br>
        <br>

        <label>Age:
            <input placeholder="Age" class="box-input" type="text" name="age" value= "<?php echo $evenement['age']; ?>" >
        </label>

        <br>
        <br>

        <label>Numéro de téléphone:
            <input placeholder="Numéro de téléphone" class="box-input" type="text" name="num_tel" value= "<?php echo $evenement['num_tel']; ?>" >
        </label>

        <br>
        <br>

        <label>Ecole: 
            <input placeholder="Ecole" type="text" class="box-input" name="ecole" value= "<?php echo $evenement ['ecole']; ?>" >
        </label>

        <br>
        <br>

        <label>
            <input class="submit" type="submit" name="submit" value="Modifier" />
        </label>
    </div>

    <div class="button-desactiver">
              <!--< class="Désactiver">Désactiver mon compte</h2>!-->
              <input type="submit" name="desactiver" value="<?= $activate_or_desactivate; ?>" id="Désactiver" class="Désactiver">

            </div>
    <div class="button-supp ">
        <!--< class="Désactiver">Désactiver mon compte</h2>!-->
        <input type="submit" name="supprimer" value="supprimer mon compte" id="supprimer" class="supprimer">

    </div>
</form>
  
</body>
</html>