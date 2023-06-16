<?php
// ne pas oublier de vérifier token
require('config.php');

session_start();

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $getid = $_GET['id'];
    $recupUsers = $conn->prepare('SELECT * FROM profil WHERE user_id = ?');
    $recupUsers->execute(array($getid));
    if ($recupUsers->rowCount() > 0) {
        if (isset($_POST['envoyer'])) {
            $message = htmlspecialchars($_POST['message']);
            $insererMessage = $conn->prepare('INSERT INTO messages_privees(user_id, destinataire_id, contenu) VALUES(?, ?, ?)');
            $insererMessage->execute(array($_SESSION['user_id'], $getid, $message));
        } elseif (isset($_POST['supprimer'])) {
            $supprimerMessage = $conn->prepare('DELETE FROM messages_privees WHERE id = :id');
            $supprimerMessage->execute(array("id" => $_POST['message_id']));
            var_dump($supprimerMessage);
        } elseif (isset($_POST['modifier'])) {
            // $nouveau_message = htmlspecialchars($_POST['nouveau_message']);
            // $insererMessage = $conn->prepare('UPDATE messages_privees SET contenu = ? WHERE id = ?');
            // $insererMessage->execute(array($nouveau_message, $_POST['message_id']));
        }
    } else {
        echo "Aucun utilisateur trouvé";
    }
} else {
    echo "Aucun identifiant trouvé";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>discussion privée</title>
    <link rel="stylesheet" type="" href="Message.css">
    <script type="text/javascript">
       /*  function salut(e) {
            console.log(e.target);
            // Sélectionner l'élément <p> ayant l'id "message_envoye"
            var paragraphe = e.target.parentNode.querySelector('input[name="message_id"]').value;
            // Récupérer l'ID du message
            var messageId = e.target.parentNode.querySelector('input[name="modifier"]').value;

            // Afficher l'ID du message dans la console
            console.log("ID du message :", messageId);

            // Cache les boutons "modifier" et "supprimer"
            document.getElementById("boutton_modifier").style.display = "none";
            document.getElementById("boutton_supprimer").style.display = "none";

            // Créer un nouvel élément <textarea>
            var zoneTexte = document.createElement('textarea');

            // Copier le contenu du paragraphe dans la zone de texte
            zoneTexte.textContent = paragraphe.textContent;

            // Remplacer le paragraphe par la zone de texte
            paragraphe.parentNode.replaceChild(zoneTexte, paragraphe);

            // Créer un nouveau bouton pour rétablir le paragraphe initial
            var boutonRetablir = document.createElement('input');
            boutonRetablir.setAttribute('type', 'submit');
            boutonRetablir.setAttribute('name', 'valider_modifier');
            boutonRetablir.setAttribute('value', 'Valider');
            boutonRetablir.onclick = function () {
                // Sélectionner la zone de texte créée précédemment
                var zoneTexte = document.querySelector('textarea');

                // Créer un nouvel élément <p>
                var paragraphe = document.createElement('p');

                // Copier le contenu de la zone de texte dans le paragraphe
                paragraphe.textContent = zoneTexte.value;

                // Remplacer la zone de texte par le paragraphe
                zoneTexte.parentNode.replaceChild(paragraphe, zoneTexte);

                // Supprimer le bouton "valider_modifier"
                boutonRetablir.parentNode.removeChild(boutonRetablir);

                // Affiche les boutons "modifier" et "supprimer"
                document.getElementById("boutton_modifier").style.display = "block";
                document.getElementById("boutton_supprimer").style.display = "block";
 */
                // Réaliser la modification du message
                // Utilisez l'ID du message récupéré pour effectuer la modification
                // Exemple de code :
                // var nouveau_message = paragraphe.textContent;
                // var formModifier = document.createElement('form');
                // formModifier.setAttribute('action', '');
                // formModifier.setAttribute('method', 'post');
                // var inputMessageId = document.createElement('input');
                // inputMessageId.setAttribute('type', 'hidden');
                // inputMessageId.setAttribute('name', 'message_id');
                // inputMessageId.value = messageId;
                // formModifier.appendChild(inputMessageId);
                // var inputNouveauMessage = document.createElement('input');
                // inputNouveauMessage.setAttribute('type', 'text');
                // inputNouveauMessage.setAttribute('name', 'nouveau_message');
                // inputNouveauMessage.value = nouveau_message;
                // formModifier.appendChild(inputNouveauMessage);
                // var inputValiderModifier = document.createElement('input');
                // inputValiderModifier.setAttribute('type', 'submit');
                // inputValiderModifier.setAttribute('name', 'valider_modifier');
                // inputValiderModifier.setAttribute('value', 'Valider');
                // formModifier.appendChild(inputValiderModifier);
                // paragraphe.parentNode.replaceChild(formModifier, paragraphe);
/*             }
        } */
    </script>
</head>
<body>
    <header class="header">
        <div class="container">
            <div class="header__logo">UNIBOOK</div>

            <form action="/recherche" method="get">
                <div class="recherche">
                    <input type="text" class="bar" name="query" placeholder="Rechercher sur UNIBOOK">
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

    <form action="" method="post">
        <textarea class="Box-message" name="message" id="message" rows="10"></textarea>
        <br><br>
        <input type="submit" name="envoyer" value="Envoyer" class="Envoyer">
    </form>

    <section id="messages">
        <?php
        $recupMessages = $conn->prepare('SELECT * FROM messages_privees WHERE user_id = ? AND destinataire_id = ? OR user_id = ? AND destinataire_id = ? ORDER BY date_creation DESC LIMIT 25');
        $recupMessages->execute(array($_SESSION['user_id'], $getid, $getid, $_SESSION['user_id']));
        while ($message = $recupMessages->fetch()) {
            if ($message['user_id'] == $_SESSION['user_id']) {
                ?>
                <form action="" method="post">
                    <p id="message_envoye" class="msg-envoye"><?= $message['contenu']; ?></p>
                    <input id="boutton_supprimer" class="msg-envoye" type="submit" name="supprimer" value="Supprimer">
                    <button id="boutton_modifier" class="msg-envoye" type="button" name="modifier"  onclick="salut(event)">Modifier</button>
                    <input type="hidden" name="message_id" value="<?= $message['id'] ?>">
                </form>
            <?php
            } elseif ($message['user_id'] == $getid) {
                ?>
                <p><?= $message['contenu']; ?></p>
            <?php
            }
        }
        ?>
    </section>
</body>
</html>

