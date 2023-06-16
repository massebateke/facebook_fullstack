<?php
require_once("config.php");

//Exécuter une requête pour récupérer toutes les publications
$sql = $conn->prepare("SELECT * FROM publication");
$sql->execute();
$resultSet = $sql->get_result();
$data = $resultSet->fetch_all(MYSQLI_ASSOC);

//Parcourir chaque publication
foreach ($data as $row) {
?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Publication</title>
</head>
<body>
   
   
   <link rel="stylesheet" href="stylepublication.css">
   <script src="Facebook-Like-Reaction-System-main/Js/jquery.min.js"></script>

   
   <link rel="stylesheet" href="Facebook-Like-Reaction-System-main/css/font-awesome.min.css">
   <link rel="stylesheet" href="Facebook-Like-Reaction-System-main/css/bootstrap.min.css">
   
   
   <div class="content">



   <!-- Afficher le contenu de la publication -->
   <div class="text">
   <?php echo $row["contenu"];?>
   </div>


   <?php
   // Récupérer les réactions associées à la publication
   $sql = "SELECT * FROM reaction WHERE publication_id = " . $row["id"];
   $resultSet = $conn->query($sql);
   $data = $resultSet->fetch_all(MYSQLI_ASSOC);
   $countLike = $resultSet->num_rows;
   ?>

      <!-- Div contenant toutes les réactions -->
      <div class="all-reaction" id="react_<?php echo $row["id"]?>">
      <!-- Images représentant les différentes réactions -->
      <img src="images/thumb.gif" class="reaction" id="thumb_<?php echo $row["id"]?>">
      <img src="images/haha.gif" class="reaction" id="haha_<?php echo $row["id"]?>">
      <img src="images/love.gif" class="reaction" id="love_<?php echo $row["id"]?>">
      <img src="images/wow.gif" class="reaction" id="wow_<?php echo $row["id"]?>">
      <img src="images/sad.gif" class="reaction" id="sad_<?php echo $row["id"]?>">
      <img src="images/angry.gif" class="reaction" id="angry_<?php echo $row["id"]?>">
   </div>


      <!-- Div contenant le bouton "J'aime" et le compteur de likes -->
      <div class="react-con" aligne="center" id="<?php echo $row["id"];?>">
      <i class="glyphicon glyphicon-thumbs-up" style="font-size:18px; margin:11px;"></i>
      </div>
      <span id="counter_<?php echo $row["id"]?>"><?php if($countLike>0){echo $countLike;}?></span>

   </div><p>

<?php
}
?>
</div>

<!-- Script JavaScript -->
<script>
$(document).ready(function(){
   // Cacher la barre des réactions initialement
   $(".all-reaction").hide();

   // Fermer la barre des réactions en cliquant en dehors
   $(document).click(function(e){
      if($(e.target).closest(".all-reaction").length===0) {
         $(".all-reaction").hide(); 
      }
   });

   // Afficher la barre des réactions au survol du bouton "J'aime"
   $(".react-con").hover(function(){
      var id = this.id;
      $("#react_"+id).show("slow");

      // Lorsqu'une réaction est cliquée
      $(".reaction").off().click(function(){
         var reactId = this.id;
         var splitId = reactId.split("_");
         var contenureact = splitId[0];
         var publication_id = splitId[1];

         // Envoyer une requête AJAX pour enregistrer la réaction
         $.ajax({
            type: "POST",
            url: "liike.php",
            data: {contenureact: contenureact, publication_id: publication_id},
            success: function(data){
               $("#counter_"+publication_id).html(data);

          // Afficher la reaction cliqué en question  
               var reactImg = "<center><img src='images/"+contenureact+".png' class='reaction'></center>"
                 $("#"+publication_id).html(reactImg);
            }
         });

         // Fermer la barre des réactions
         $(".all-reaction").hide();
      });
   });
});
</script>

</body>
</html>
