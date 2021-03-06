<html lang="fr">
    <head>
        <?php
            require_once './src/view/header.php';
        ?>
    </head>
    <body>
        <?php require_once './src/view/menu.php'; ?>
        <h1>Statistiques (pour votre restaurant)</h1>
        <div>
            <?php
                if($resultviande==$resultpoisson)
                {
                    if($resultviande[0]<$resultpoisson[0])
                        echo("Les plats à base de poisson sont plus consommés");
                    else
                        echo("Les plats à base de viande sont plus consommés");
                }
                
            ?>
            <ul>
                <li>Consommation plats a base de viande : <?php echo $resultviande ?></li>
                <li>Consommation plats a base de poisson : <?php echo $resultpoisson ?></li>
            </ul>
        </div>
        <br>
        <div>
            <p>Les plats les plus commandés :</p>
            <ul>
            <?php
                $iMax = 5;
                if(count($plats)<5)
                {
                    $iMax = count($plats);
                }
                for($i=0;$i<$iMax;$i++)
                {
                    $temp = $plats[$i];
                    echo '<li>'.$temp['nomplat'].'  =>  '.$temp['somme'].'</li>';
                }
            ?>
            </ul>
        </div>
		<br>
		<div>
            <p>Somme moyenne dépensée sur les desserts pour chaque commande :<strong>
			<?php echo $moyenneDessert;?> €</strong> (sur <?php echo $result[0]['nbcommande']?> commandes)
			</p>
        </div>
    </body>
</html>