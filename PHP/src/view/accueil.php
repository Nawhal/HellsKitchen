<html lang="fr">
    <head>
        <?php
            require_once './src/view/header.php';
        ?>
    </head>
    <body>
        <?php require_once './src/view/menu.php'; ?>
	<div>
            <h1>Restaurants</h1>
            <ul>
				<?php
					foreach($res as $key => $val)
					{
						echo '<li> <a href=\'?action=voirCartes&id='.$val['idrestaurant'].'\'>'.$val['nomrestaurant'].'</a> a '.$val['ville'].', '.$val['pays'].' - '.$val['adresse'].'</li>';
					}
				?>
            </ul>
	</div>
    </body>
</html>
