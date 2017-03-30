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
            <ul>
                <?php
                    foreach($cartes as $carte)
                    {
                        echo '<li>['.$carte['dateDebut'].' - '.$carte['dateFin'].'] '.$carte['nomCarte']
                                . ' (<a href=\'?action=modifCarte&id='.$carte['idCarte'].'\'>Modifier</a> /'
                                . ' <a href=\'?action=suppCarte&id='.$carte['idCarte'].'\'>Supprimer</a>) </li>';
                    }
                ?>
            </ul>
	</div>
    </body>
</html>
