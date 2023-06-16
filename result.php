<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Résultats de recherche</title>
    <style>
        /* Styles CSS pour améliorer l'apparence des résultats */
        .result {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            background-color: #f9f9f9;
        }
        .result h3 {
            margin: 0;
            font-size: 18px;
        }
        .result p {
            margin: 0;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Résultats de recherche</h1>

    <?php
    // Vérifie si des résultats ont été retournés
    if (!empty($results)) {
        foreach ($results as $result) {
            echo '<div class="result">';
            echo '<h3>' . htmlspecialchars($result["name"]) . ' ' . htmlspecialchars($result["first_name"]) . '</h3>';
            echo '<p>' . htmlspecialchars($result["genre"]) . '</p>';
            echo '</div>';
        }
    } else {
        echo '<p>Aucun résultat trouvé.</p>';
    }
    ?>

</body>
</html>
