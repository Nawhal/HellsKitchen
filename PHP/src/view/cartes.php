<html lang="fr">
    <head>
        <?php
            require_once './src/view/header.php';
        ?>
    </head>
    <body>
	<?php require_once './src/view/menu.php'; ?>
	<div>
            <h1>Cartes</h1>
                <?php			
					
					foreach($cartes as $key => $val)
					{
						echo '<h2>'.$val['nomcarte'].'</h2>';
						echo '<h3>Menus</h3>';

						$query = "SELECT menu.nomMenu,prixElement.prixElement
									FROM menu,prixElement,carte
										WHERE prixElement.idElement=menu.idElement
										AND prixElement.idCarte=Carte.idCarte
										AND prixElement.idCarte=".$val['idcarte'].";";
						$co->executeQuery($query);
      					$menus = $co->getResults();
						
						foreach($menus as $key => $value)
						{
							echo '<h4>'.$value['nommenu'].' - '.$value['prixelement'].'&euro;</h4>';
							$query = "SELECT plat.nomPlat, menu.nomMenu
										FROM plat,menu,assocMenuPlat
											WHERE plat.idElement=assocMenuPlat.idPlat
											AND assocMenuPlat.idMenu=menu.idElement
											AND menu.nomMenu='".$value['nommenu']."'
												ORDER BY plat.nomPlat;";
							$co->executeQuery($query);
      						$platsMenu = $co->getResults();
							echo '<ul>';
							foreach($platsMenu as $key => $value)
							{
								echo '<li>'.$value['nomplat'].'</li>';
							}
							echo '</ul>';
						}

						echo '<h3>A la carte</h3>';
						echo '<h4>Plats</h4>';
						$query = "SELECT plat.nomPlat,prixElement.prixElement
									FROM plat,prixElement,carte
										WHERE plat.idElement=prixElement.idElement
										AND Carte.idCarte=prixElement.idCarte
										AND prixElement.idCarte=".$val['idcarte']."
											ORDER BY plat.nomPlat;";
						$co->executeQuery($query);
      					$plats = $co->getResults();

						echo '<ul>';
						foreach($plats as $key => $value)
						{
							echo '<li>'.$value['nomplat'].' - '.$value['prixelement'].'&euro;</li>';
						}
						echo '</ul>';

						echo '<h4>Boissons</h4>';
						$query = "SELECT boisson.nomBoisson,prixElement.prixElement
									FROM boisson,boissonOfferte,prixElement,carte
										WHERE boissonOfferte.nomBoisson = boisson.nomBoisson
										AND prixElement.idElement=boissonOfferte.idElement
										AND prixElement.idCarte=Carte.idCarte
										AND prixElement.idCarte=".$val['idcarte']."
											ORDER BY boisson.nomBoisson;";
						$co->executeQuery($query);
      					$boissons = $co->getResults();
						
						echo '<ul>';
						foreach($boissons as $key => $value)
						{
							echo '<li>'.$value['nomboisson'].' - '.$value['prixelement'].'&euro;</li>';
						}
						echo '</ul>';
					}
					/*	//Toutes les cartes du restaurant voulu
        $query ="SELECT Carte.idCarte,Carte.nomCarte
					FROM Carte,periodeCarte
						WHERE Carte.idCarte=periodeCarte.idCarte
						AND periodeCarte.idRestaurant=".$_GET['id'].";";
        $co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
        $co->executeQuery($query);
        $cartes = $co->getResults();

        //Tous les menus de toutes les cartes du restaurant
        $menus = array();
        foreach($cartes as $key => $val)
        {
            $query = "SELECT menu.nomMenu,prixElement.prixElement
	                    FROM menu,prixElement,carte
		                    WHERE prixElement.idElement=menu.idElement
		                    AND prixElement.idCarte=Carte.idCarte
		                    AND prixElement.idCarte=".$val['idcarte'].";";
            $co->executeQuery($query);
            array_push($menus, $co->getResults());
        } var_dump($menus);
        
        //Tous les plats de chaque menu
		$platsDeMenu = array();
        foreach($menus as $key => $val)
        {
            $query = "SELECT plat.nomPlat, menu.nomMenu
	                    FROM plat,menu,assocMenuPlat
		                    WHERE plat.idElement=assocMenuPlat.idPlat
		                    AND assocMenuPlat.idMenu=menu.idElement
		                    AND menu.nomMenu=\"".$val['nommenu']."\"
			                    ORDER BY menu.nomMenu;";
            $co->executeQuery($query);
            array_push($platsDeMenu, $co->getResults());
        }
        
        //Tous les plats (indépendamment d'un menu) de toutes les cartes du restaurant
        $plats = array();
        foreach($cartes as $key => $val)
        {
            $query = "SELECT plat.nomPlat,prixElement.prixElement
	                    FROM plat,prixElement,carte
		                    WHERE plat.idElement=prixElement.idElement
		                    AND Carte.idCarte=prixElement.idCarte
		                    AND prixElement.idCarte=".$val['idcarte'].";";
            $co->executeQuery($query);
            array_push($plats, $co->getResults());
        }

        //Toutes les boissons de toutes les cartes du restaurant
        $boissons = array();
        foreach($cartes as $key => $val)
        {
            $query = "SELECT boisson.nomBoisson,prixElement.prixElement
	                    FROM boisson,boissonOfferte,prixElement,carte
		                    WHERE boissonOfferte.nomBoisson = boisson.nomBoisson
		                    AND prixElement.idElement=boissonOfferte.idElement
		                    AND prixElement.idCarte=Carte.idCarte
		                    AND prixElement.idCarte=".$val['idcarte'].";";
            $co->executeQuery($query);
            array_push($boissons, $co->getResults());
        }*/
                    /*foreach($res as $key => $val)
                    {
                        			
                    }*/
                ?>
	</div>
    </body>
</html>
