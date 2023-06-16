<?php

require('config.php');


$etat = filter_input(INPUT_GET, "etat");
if($etat){
    return True;
}
?>


<!-- ajouter dans le lien vers profil le lien <a href="profil.php?etat=<?php //echo $row["etat"]; ?>"> -->




