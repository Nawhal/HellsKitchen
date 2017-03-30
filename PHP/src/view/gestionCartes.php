<html lang="fr">
    <head>
        <?php
            require_once './src/view/header.php';
        ?>
    </head>
    <body>
        <?php require_once './src/view/menu.php'; ?>
	<div>
            <h1>Gestion des cartes</h1>
	    <form action="" method="get">
		<button name="action" value="addCarte" type="submit">Ajouter une carte</button>
	    </form>
            <ul>
                <?php
                    foreach($cartes as $carte)
                    {
                        echo '<li>['.$carte['datedebut'].' - '.$carte['datefin'].'] '.$carte['nomcarte']
                                . ' (<a href=\'?action=modifCarte&id='.$carte['idcarte'].'\'>Modifier</a> /'
                                . ' <a href=\'?action=suppCarte&id='.$carte['idcarte'].'\'>Supprimer</a>) </li>';
                    }
                ?>
            </ul>
	</div>
    </body>
</html>
