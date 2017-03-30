<html lang="fr">
    <head>
        <?php
            require_once './src/view/header.php';
        ?>
    </head>
    <body>
        <?php require_once './src/view/menu.php'; ?>
	<div>
            <?php
                if (!empty($carte))
                {
                    echo '<h1>Modification de carte</h1>';
                    $modif = true;
                }
                else
                {
                    echo '<h1>Ajout de carte</h1>';
                    $modif = false;
                }
            ?>
            <form action="" method="get">
                <input name="action" type="hidden" value="enregistrerCarte">
                <input name="idCarte" type="hidden" value="<?php echo ($modif? $carte['idcarte'] : '-1') ?>">
                Nom de la Carte : <input name="nomCarte" type="text" placeholder="Nom de la carte" size="15" <?php echo ($modif? 'value = "'.$carte['nomcarte'].'"' : '') ?>><br/>
                Date de début : <input name="dateDebut" type="text" placeholder="jj/mm/aaaa" size="15" <?php echo ($modif? 'value = "'.$carte['datedebut'].'"' : '') ?>><br/>
                Date de fin : <input name="dateFin" type="text" placeholder="jj/mm/aaaa" size="15" <?php echo ($modif? 'value = "'.$carte['datefin'].'"' : '') ?>>
                <br/>
                <input type="submit" value="Enregistrer">
            </form>
        
	</div>
    </body>
</html>
