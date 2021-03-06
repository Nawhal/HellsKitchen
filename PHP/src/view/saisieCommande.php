<html lang="fr">
    <head>
        <?php
            require_once './src/view/header.php';
        ?>
    </head>
    <body>
        <?php require_once './src/view/menu.php'; ?>
        <h1>Saisie d'une commande</h1>
        <form action="" method="get">
            <div>
                <?php echo "$nomCarte => du $dateDebut au $dateFin"; ?>
            </div>
            <h2>Menus</h2>
            <div>
                <?php
                    foreach($menus as $row)
                    {
                       echo '<div><input type="number" value="0" min="0" style="width: 5em;" name="menus['.$row['idelement'].']"> - '.$row['nommenu'].'</div>';
                    }
                ?>
            </div>
            <br><hr>
            <h2>Plats a la carte</h2>
            <div>
                <?php
                    foreach($plats as $row)
                    {
                       echo '<div><input type="number" value="0" min="0" style="width: 5em;" name="plats['.$row['idelement'].']"> - '.$row['nomplat'].'</div>';
                    }
                ?>
            </div>
            <br><hr>
            <h2>Boissons a la carte</h2>
            <div>
                <?php
                    foreach($boissons as $row)
                    {
                       echo '<div><input type="number" value="0" min="0" style="width: 5em;" name="boissons['.$row['idelement'].']"> - '.$row['nomboisson'].'</div>';
                    }
                ?>
            </div>
            <br><hr>
            <input type="hidden" name="action" value="enregistrerCom">
            <input type="submit" value="Valider la commande">
        </form>
    </body>
</html>