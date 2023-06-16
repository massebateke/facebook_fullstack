<?php
require('config.php');

$_SESSION["user_id"] = 1; // Session de l'utilisateur, a changer ??
$sessionId = $_SESSION["user_id"];
$user = mysqli_fetch_assoc(mysqli_query($conn, "SELECT * FROM profil WHERE user_id = $sessionId"));

if (isset($_FILES["photo_profil"]["name"])) { // Vérifie si le champ d'upload "photo_profil" est défini
    $user_id = $_POST["user_id"]; // Récupère l'ID de l'utilisateur depuis le champ de formulaire "user_id"
    $name = $_POST["name"]; // Récupère le nom de l'utilisateur depuis le champ de formulaire "name"

    $imageName = $_FILES["photo_profil"]["name"]; // Récupère le nom de l'image uploadée depuis le champ de formulaire "photo_profil"
    $imageSize = $_FILES["photo_profil"]["size"]; // Récupère la taille de l'image uploadée depuis le champ de formulaire "photo_profil"
    $tmpName = $_FILES["photo_profil"]["tmp_name"]; // Récupère le nom temporaire de l'image uploadée depuis le champ de formulaire "photo_profil"

    // Validation de l'image
    $validImageExtension = ['jpg', 'jpeg', 'png']; // Les extensions d'images autorisées
    $imageExtension = explode('.', $imageName); // Sépare le nom de l'image et son extension
    $imageExtension = strtolower(end($imageExtension)); // Convertit l'extension en minuscules

    if (!in_array($imageExtension, $validImageExtension)) { // Vérifie si l'extension de l'image est autorisée
        echo "
        <script>
            alert('Invalid Image Extension');
            document.location.href = '../banniere/profilbanniere.php';
        </script>";
    } elseif ($imageSize > 1200000) { // Vérifie si la taille de l'image est supérieure à 1,2 Mo
        echo "
        <script>
            alert('Image Size Is Too Large');
            document.location.href = '../banniere/profilbanniere.php';
        </script>";
    } else {
        $newImageName = $name . " - " . date("Y.m.d") . " - " . date("h.i.sa"); // Génère un nouveau nom d'image 
        $newImageName .= "." . $imageExtension; // Ajoute l'extension de l'image au nouveau nom généré
        $query = "UPDATE profil SET photo_profil ='$newImageName' WHERE user_id= $user_id"; // Met à jour la base de données avec le nouveau nom de l'image
        mysqli_query($conn, $query); // Exécute la requête de mise à jour
        mysqli_query($conn, $query); // Exécute la requête de mise à jour
        move_uploaded_file($tmpName, 'img/' . $newImageName);
        echo "
        <script>
            document.location.href = '../banniere/profilbanniere.php';
        </script>";
    }
}

if (isset($_FILES["photo_banniere"]["name"])) {
    // Vérifie si le champ d'upload "photo_banniere" est défini
    $user_id = $_POST["user_id"];
    $name = $_POST["name"];
    $imageName = $_FILES["photo_banniere"]["name"];
    $imageSize = $_FILES["photo_banniere"]["size"];
    $tmpName = $_FILES["photo_banniere"]["tmp_name"];

    // Validation de l'image
    $validImageExtension = ['jpg', 'jpeg', 'png']; // Les extensions d'images autorisées
    $imageExtension = explode('.', $imageName); // Sépare le nom de l'image et son extension
    $imageExtension = strtolower(end($imageExtension)); // Convertit l'extension en minuscules

    if (!in_array($imageExtension, $validImageExtension)) {
        // Vérifie si l'extension de l'image est autorisée
        echo "
        <script>
            alert('Invalid Image Extension');
            document.location.href = '../banniere/profilbanniere.php';
        </script>";
    } elseif ($imageSize > 1200000) {
        // Vérifie si la taille de l'image est supérieure à 1,2 Mo
        echo "
        <script>
            alert('Image Size Is Too Large');
            document.location.href = '../banniere/profilbanniere.php';
        </script>";
    } else {
        $newImageName = $name . " - " . date("Y.m.d") . " - " . date("h.i.sa"); // Génère un nouveau nom d'image 
        $newImageName .= "." . $imageExtension; // Ajoute l'extension de l'image au nouveau nom généré
        $query = "UPDATE profil SET photo_banniere ='$newImageName' WHERE user_id= $user_id"; // Met à jour la base de données avec le nouveau nom de l'image
        mysqli_query($conn, $query); // Exécute la requête de mise à jour
        move_uploaded_file($tmpName, 'img/' . $newImageName);
        echo "
        <script>
            document.location.href = '../banniere/profilbanniere.php';
        </script>";
    }
}

$user_id = $user["user_id"]; // Obtient l'ID de l'utilisateur
$name = $user["name"]; // Obtient le nom de l'utilisateur
$image_profil = $user["photo_profil"]; // Obtient le nom de l'image de profil de l'utilisateur
$image_banniere = $user["photo_banniere"]; // Obtient le nom de l'image de bannière de l'utilisateur
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Image Profile</title>
    <link rel="stylesheet" href="styleprofilbanniere.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"><!-- Liaison avec le fichier CSS de Font Awesome -->
</head>

<body>

    <form class="form" id="form-banniere" action="" enctype="multipart/form-data" method="post" >
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
    <form class="form" id="form-profil" action="" enctype="multipart/form-data" method="post">
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
    </div>
    
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
</body>

</html>
