<?php
// Liste des employés : gestion du personnel supprimer /ajouter un gas
// Ajouter une commande
/*
SELECT idElement, quantite, idCommande
FROM `quantiteELement`
WHERE idElement IN 
	(SELECT idElement FROM Plat WHERE dessert=TRUE)
    
SELECT idElement, prixElement
FROM `prixElements`
WHERE  

(SELECT cartes.idCarte
FROM cartes, periodesCartes
WHERE dateDebut<TO_DATE('01-02-2013') AND dateFin>TO_DATE('01-02-2013') AND idRestaurant=1)
 *  */

class Controller
{
    public function __construct()
    {
        $actionsClient  = array('seConnecter', 'seDeconnecter','sansAction','voirCartes');
        $actionsServeur = array('saisirCom', 'enregistrerCom');
        $actionsManager = array('voirStat', 'voirEmp', 'suppEmp', 'addEmp');
        
        session_start();
        
        try
        {
                $action = isset($_REQUEST['action']) ? $_REQUEST['action']:'sansAction';
                switch($action)
                {
                        case in_array($action, $actionsClient):
                                $this->actionClient($action);
                                break;
                        case in_array($action, $actionsServeur):
                                if($this->isServeur())
                                {
                                    $this->actionServeur($action);
                                    break;
                                }
                                throw new Exception("Droits Insuffisants");
                                break;
                        case in_array($action, $actionsManager):
                                if($this->isManager())
                                {
                                    $this->actionManager($action);
                                    break;
                                }
                                throw new Exception("Droits Insuffisants");
                                break;
                        default:
                                $this->afficherAccueil();
                }
        }
        catch(PDOException $e)
        {
            echo "<p> <strong>ERREUR BDD : </strong><br>".$e->getMessage()."</p>";
            die();
        }
        catch(Exception $e)
        {
            echo "<p> <strong>Erreur : </strong>".$e->getMessage()."</p>";
            die();
        }
    }
    
    private function actionClient($action)
    {
        switch($action)
        {
            case 'seConnecter':
                $this->seConnecter();
                $this->afficherAccueil();
                break;
            case 'seDeconnecter':
                $this->deconnexion();
                $this->afficherAccueil();
                break;
            case 'voirCartes':
                $this->afficherCartes();
                break;
            case 'sansAction':
                $this->afficherAccueil();
                break;
            default:
                //erreur
        }
    }
    
    private function actionServeur($action)
    {
        switch($action)
        {
            case 'saisirCom':
                $this->afficherVueSaisieCommande();
                break;
            case 'enregistrerCom':
                $this->enregistrerCommande();
                break;
            default:
                //erreur
        }
    }
    
    private function actionManager($action)
    {
        switch($action)
        {
            case 'voirStat':
                $this->afficherStat();
                break;
            case 'voirEmp':
                $this->listerEmployes();
                break;
            case 'suppEmp':
                $this->suppEmploye();
                $this->listerEmployes();
                break;
            case 'addEmp':
                $this->addEmploye();
                $this->listerEmployes();
                break;
            default:
                //erreur
        }
    }
    
    
    private function isManager()
    {
        if(isset($_SESSION['login']) && isset ($_SESSION['role']) && $_SESSION['role']=='manager')
        {
            return true;
        }
        return false;
    }
    
    private function isServeur()
    {
        if(isset($_SESSION['login']) && isset ($_SESSION['role']) && $_SESSION['role']=='serveur')
        {
            return true;
        }
        return false;
    }
    
    private function seConnecter()
    {
        if(empty($_REQUEST['login']))
        {
            throw new Exception("Login absent");
        }
        $splitLogin = preg_split("/\./", $_REQUEST['login'], 2);
        if(count($splitLogin) != 2)
        {
            throw new Exception("Login incorrect");
        }
        $prenom = filter_var($splitLogin[0], FILTER_SANITIZE_STRING);
        $nom = filter_var($splitLogin[1], FILTER_SANITIZE_STRING);
        $dbInfos = Config::getDataBaseInfos();
        $co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
        
        $co->executeQuery("SELECT idemploye, nom, prenom, idrestaurant FROM manager WHERE nom = ? AND prenom = ?;", array( 1 => array($nom, PDO::PARAM_STR),
                                                                                        2 => array($prenom, PDO::PARAM_STR)
                                                                                      ));
        $result = $co->getResults();
        if(count($result) < 1 || $result[0]['nom']!=$nom || $result[0]['prenom']!=$prenom)
        {
            $co->executeQuery("SELECT idemploye, nom, prenom, idrestaurant FROM serveur WHERE nom = ? AND prenom = ?;", array( 1 => array($nom, PDO::PARAM_STR),
                                                                                        2 => array($prenom, PDO::PARAM_STR)
                                                                                      ));
            
            $result = $co->getResults();
            if(count($result) < 1 || $result[0]['nom']!=$nom || $result[0]['prenom']!=$prenom)
            {
                throw new Exception("Login incorrect");
            }
            $_SESSION['login']=$result[0]['prenom'].'.'.$result[0]['nom'];
            $_SESSION['role']='serveur';
            $_SESSION['idRestau']=$result[0]['idrestaurant'];
            return;
        }
        $_SESSION['login']=$result[0]['prenom'].'.'.$result[0]['nom'];
        $_SESSION['role']='manager';
        $_SESSION['idRestau']=$result[0]['idrestaurant'];
    }
    
    private function deconnexion()
    {
        session_unset();
	session_destroy();
	$_SESSION = array();
    }
    
    private function afficherVueSaisieCommande()
    {
        $dbInfos = Config::getDataBaseInfos();
        $co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
        $co->executeQuery("SELECT idcarte, datedebut, datefin FROM periodeCarte WHERE idRestaurant = ? AND dateDebut<NOW() AND dateFin>=NOW()", array(1 => array($_SESSION['idRestau'],PDO::PARAM_INT )));
        $result = $co->getResults();
        if(count($result)<1)
        {
            throw new Exception("Aucune carte en cours pour votre restaurant.");
        }
        $idCarte = $result[0]['idcarte'];
        $dateDebut = $result[0]['datedebut'];
        $dateFin = $result[0]['datefin'];
        $co->executeQuery("SELECT nomcarte FROM carte WHERE idCarte= ?;", array(1 => array($idCarte, PDO::PARAM_INT)));
        $result = $co->getResults();
        $nomCarte = $result[0]['nomcarte'];
        $co->executeQuery("SELECT idelement, nommenu FROM menu WHERE idElement IN (SELECT idElement FROM prixElement WHERE idCarte=?);", array(1 => array($idCarte, PDO::PARAM_INT)));
        $menus = $co->getResults();
        $co->executeQuery("SELECT idelement, nomplat FROM plat WHERE idElement IN (SELECT idElement FROM prixElement WHERE idCarte=?);", array(1 => array($idCarte, PDO::PARAM_INT)));
        $plats = $co->getResults();
        $co->executeQuery("SELECT idelement, nomboisson FROM boissonOfferte WHERE idElement IN (SELECT idElement FROM prixElement WHERE idCarte=?);", array(1 => array($idCarte, PDO::PARAM_INT)));
        $boissons = $co->getResults();
        require("./src/view/saisieCommande.php");
    }
    
    private function enregistrerCommande()
    {
        $dbInfos = Config::getDataBaseInfos();
        $co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
        $co->executeQuery("SELECT MAX(idCommande) AS maxid FROM commande;");
        $result = $co->getResults();
        $idCommande = $result[0]['maxid'];
        $idCommande++;
        $co->executeQuery("INSERT INTO commande(idCommande, idRestaurant, dateCommande) VALUES (?, ?, NOW());", array(1 => array($idCommande, PDO::PARAM_INT), 
                                                                                                                      2 => array($_SESSION['idRestau'], PDO::PARAM_INT)));
        if(isset($_GET['menus']))
            {$this->enregisterQuantiteCom($_GET['menus'], $idCommande, $co);}
        if(isset($_GET['plats']))
            {$this->enregisterQuantiteCom($_GET['plats'], $idCommande, $co);}
        if(isset($_GET['boissons']))
            {$this->enregisterQuantiteCom($_GET['boissons'], $idCommande, $co);}
        require("./src/view/commandeOk.php");
    }
    
    private function enregisterQuantiteCom(array $element, $idCommande, $co)
    {
       foreach($element as $key => $qte)
       {
           if($qte > 0)
           {
               $co->executeQuery("INSERT INTO quantiteElement(idElement, idCommande, idRestaurant, quantite) VALUES (?,?,?,?);", array(
                                                                                                            1 => array($key, PDO::PARAM_INT),
                                                                                                            2 => array($idCommande, PDO::PARAM_INT),
                                                                                                            3 => array($_SESSION['idRestau'], PDO::PARAM_INT),
                                                                                                            4 => array($qte, PDO::PARAM_INT)));
           }
       }
    }
    
    private function listerEmployes()
    {
        $dbInfos = Config::getDataBaseInfos();
        $co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
        $co->executeQuery("(SELECT idemploye, nom, prenom, 'manager' AS type FROM manager WHERE idRestaurant=?)
                            UNION
                            (SELECT idemploye, nom, prenom, 'cuisinier' AS type FROM cuisinier WHERE idRestaurant=?)
                            UNION
                            (SELECT idemploye, nom, prenom, 'serveur' AS type FROM serveur WHERE idRestaurant=?)
                            ORDER BY nom,prenom;", array(
                                                        1 => array($_SESSION['idRestau'], PDO::PARAM_INT),
                                                        2 => array($_SESSION['idRestau'], PDO::PARAM_INT),
                                                        3 => array($_SESSION['idRestau'], PDO::PARAM_INT)));
        $employes = $co->getResults();
        require("./src/view/vueEmployes.php");
    }
    
    private function suppEmploye()
    {
        if(empty($_GET['id']) || empty($_GET['type']))
        {
            return;
        }
        $type = $_GET['type'];
        $id = $_GET['id'];
        $dbInfos = Config::getDataBaseInfos();
        $co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
        switch($type)
        {
            case 'cuisinier':
                $co->executeQuery("DELETE FROM cuisinier WHERE idEmploye=?;", array(1=> array($id,PDO::PARAM_INT)));
                break;
            case 'serveur':
                $co->executeQuery("DELETE FROM serveur WHERE idEmploye=?;", array(1=> array($id,PDO::PARAM_INT)));
                break;
            case 'manager':
                $co->executeQuery("DELETE FROM manager WHERE idEmploye=?;", array(1=> array($id,PDO::PARAM_INT)));
                break;
            default:
                return;
        }
    }
    
    private function addEmploye()
    {
        if(empty($_GET['nom']) || empty($_GET['prenom']) || empty($_GET['type']) || empty($_GET['datenais']))
        {
            return;
        }
        date_default_timezone_set('Europe/Paris');
        $date = DateTime::createFromFormat('d/m/Y', $_GET['datenais']);
        if($date == null)
            return;
        $date = $date->format('Y-m-d');
        $nom = ucfirst(strtolower($_GET['nom']));
        $prenom = ucfirst(strtolower($_GET['prenom']));
        $type = $_GET['type'];
        $accueil = (isset($_GET['okaccueil']) && $_GET['okaccueil']=='ok') ? true : false;
        $spec = !empty($_GET['spec']) ? $_GET['spec'] : '';
        $dbInfos = Config::getDataBaseInfos();
        $co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
        $co->executeQuery("(SELECT idemploye FROM cuisinier)"
                        . "UNION (SELECT idemploye FROM manager)"
                        . "UNION (SELECT idemploye FROM serveur)"
                        . "ORDER BY idemploye DESC;");
        $idemp = $co->getResults();
        $idemp = count($idemp) < 1 ? 1 : $idemp[0]['idemploye'];
        $idemp++;
        switch($type)
        {
            case 'cuisinier':
                $co->executeQuery("INSERT INTO cuisinier(idEmploye, nom, prenom, dateNaissance, dateAnciennete, specialite, idRestaurant) VALUES (?,?,?,?, NOW(), ?,?);", array(
                    1 => array($idemp, PDO::PARAM_INT),
                    2 => array($nom, PDO::PARAM_STR),
                    3 => array($prenom, PDO::PARAM_STR),
                    4 => array($date, PDO::PARAM_STR),
                    5 => array($spec, PDO::PARAM_STR),
                    6 => array($_SESSION['idRestau'], PDO::PARAM_INT)
                ));
                break;
            case 'manager':
                $co->executeQuery("INSERT INTO manager(idEmploye, nom, prenom, dateNaissance, dateAnciennete, idRestaurant) VALUES (?,?,?,?, NOW(),?);", array(
                    1 => array($idemp, PDO::PARAM_INT),
                    2 => array($nom, PDO::PARAM_STR),
                    3 => array($prenom, PDO::PARAM_STR),
                    4 => array($date, PDO::PARAM_STR),
                    5 => array($_SESSION['idRestau'], PDO::PARAM_INT)
                ));
                break;
            case 'serveur':
                $co->executeQuery("INSERT INTO serveur(idEmploye, nom, prenom, dateNaissance, dateAnciennete, authorisationAccueil, idRestaurant) VALUES (?,?,?,?, NOW(), ?,?);", array(
                    1 => array($idemp, PDO::PARAM_INT),
                    2 => array($nom, PDO::PARAM_STR),
                    3 => array($prenom, PDO::PARAM_STR),
                    4 => array($date, PDO::PARAM_STR),
                    5 => array($accueil, PDO::PARAM_BOOL),
                    6 => array($_SESSION['idRestau'], PDO::PARAM_INT)
                ));
                break;
            default:
                return;
        }
    }
    
    private function afficherStat()
    {
        $dbInfos = Config::getDataBaseInfos();
        $co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
        $co->executeQuery("select sum(quantiteElement.quantite) from commande,quantiteElement,element,plat where commande.idRestaurant=? AND commande.idCommande=quantiteElement.idCommande AND quantiteElement.idElement=element.idElement AND element.idelement=plat.idElement AND plat.categorie='viande';", array( 1 => array($_SESSION['idRestau'], PDO::PARAM_INT)));
        $resultviande = $co->getResults();
	$co->executeQuery("select sum(quantiteElement.quantite) from commande,quantiteElement,element,plat where commande.idRestaurant=? AND commande.idCommande=quantiteElement.idCommande AND quantiteElement.idElement=element.idElement AND element.idelement=plat.idElement AND plat.categorie='poisson';", array( 1 => array($_SESSION['idRestau'], PDO::PARAM_INT)));
        $resultpoisson = $co->getResults();
        if($resultviande[0][0]==null)
            $resultviande[0][0]=0;
        if($resultpoisson[0][0]==null)
            $resultpoisson[0][0]=0;
        $resultviande = $resultviande[0][0];
        $resultpoisson = $resultpoisson[0][0];

        $co->executeQuery("select plat.nomplat,SUM(quantiteElement.quantite) AS somme FROM commande,quantiteElement,element,plat WHERE commande.idRestaurant=1 AND commande.idCommande=quantiteElement.idCommande AND quantiteElement.idElement=element.idElement AND element.idElement=plat.idElement GROUP BY nomPlat ORDER BY somme;");
        $plats = $co->getResults();
	require('./src/view/vueStats.php');
     }
     
     
    private function afficherAccueil()
    {
        $dbInfos = Config::getDataBaseInfos();
        $query ="SELECT * FROM restaurant";
        $co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
        $co->executeQuery($query);
        $res = $co->getResults();
        require("./src/view/accueil.php");
    }

    private function afficherCartes()
    {
        $dbInfos = Config::getDataBaseInfos();

		//Toutes les cartes du restaurant voulu
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
        }
		

        require("./src/view/cartes.php");
    }
    
}
