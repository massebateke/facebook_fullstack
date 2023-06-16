<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require('config.php');

try {
    $pdo = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo 'Erreur de connexion : ' . $e->getMessage();
}

function searchPeople($searchTerm) {
    global $pdo; // Utilisez la connexion à la base de données globale

    $results = array(); // Tableau pour stocker les résultats

    // Préparation de la requête SQL avec des paramètres préparés
    $query = "SELECT * FROM Profil WHERE name LIKE ? OR first_name LIKE ?";
    $stmt = $pdo->prepare($query);
    
    // Validation et échappement des données entrées par l'utilisateur
    $searchTerm = '%' . $searchTerm . '%';
    $stmt->bindParam(1, $searchTerm, PDO::PARAM_STR);
    $stmt->bindParam(2, $searchTerm, PDO::PARAM_STR);

    // Exécution de la requête préparée
    $stmt->execute();

    // Récupération des résultats de la requête
    

    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $results;
}

$searchTerm = filter_input(INPUT_GET,"searchTerm");// Terme de recherche
$searchTerm = trim($searchTerm);

$results = searchPeople($searchTerm);

// Affichage des résultats
/* foreach ($results as $result) {
    echo "Nom: " . htmlspecialchars($result["name"]) . "<br>";
    echo "Prénom: " . htmlspecialchars($result["first_name"]) . "<br>";
    echo "Genre: " . htmlspecialchars($result["genre"]) . "<br>";
    echo "Age: " . htmlspecialchars($result["age"]) . "<br>";
    echo "Etablissement: " . htmlspecialchars($result["ecole"]) . "<br>";
    echo "Numéro de téléphone: " . htmlspecialchars($result["num_tel"]) . "<br>";
    echo "<br>";
} */

// Fermeture de la connexion à la base de données
$pdo = null;
?>


<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1.0">  
<link rel="stylesheet" href= "./CSS/profil.css">
  <title>Connexion</title>
</head>
<body>
  <header class="header">
    <div class="container">
      <div class="Titre"><a href="fildactu.php">UNIBOOK</a></div>

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
              <h2><a href="#">Mon profil</a></h2>
              <h3 class="titre-AGP">Amis</h3>
              <p><a href="listeamis.php">Afficher ma liste d'amis</a> </p>
              <hr>
              <h3 class="titre-AGP">Groupes</h3>
              <p><a href="#">Afficher mes groupes</a></p>
              <hr>
              <h3 class="titre-AGP">Pages</h3>
              <p><a href="#">Afficher mes pages</a></p>
        </div>
    </div>
  </header>

    <div class="none">
      <?php foreach ($results as $result) {
        echo "Nom d'utilisateur: " . htmlspecialchars($result["username"]) . "<br>";
        echo "Nom: " . htmlspecialchars($result["name"]) . "<br>";
        echo "Prénom: " . htmlspecialchars($result["first_name"]) . "<br>";
        echo "Genre: " . htmlspecialchars($result["genre"]) . "<br>";
        echo "Age: " . htmlspecialchars($result["age"]) . "<br>";
        echo "Etablissement: " . htmlspecialchars($result["ecole"]) . "<br>";
        echo "Numéro de téléphone: " . htmlspecialchars($result["num_tel"]) . "<br>";
        echo "<br>";
      } ?>
    </div>

<form method="POST">
  <div class="profil">
        
    <h1><p class="box-input" name="username"> <?php echo "" . htmlspecialchars($result["username"])?></p></h1>

    <p class="box-input" name="first_name"> <?php echo "Nom: " . htmlspecialchars($result["name"])?></p>
    <p class="box-input" name="name" ><?php echo "Prénom: " . htmlspecialchars($result["first_name"]) ?></p>
    <p class="box-input" name="genre"> <?php echo "Genre: " . htmlspecialchars($result["genre"]) ?></p>
    <p class="box-input" name="age"><?php echo "Age: " . htmlspecialchars($result["age"]) ?></p>
    <p class="box-input" name="num_tel" ><?php echo "Numéro de téléphone: " . htmlspecialchars($result["num_tel"]) ?></p>
    <p class="box-input" name="ecole" ><?php echo "Etablissement: " . htmlspecialchars($result["ecole"]) ?></p>
  </div>
</form>
  
</body>
</html>