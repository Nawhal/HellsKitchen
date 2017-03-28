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
        $actionsClient 	= array('seConnecter', 'seDeconnecter','sansAction','voirCartes');
        $actionsServeur = array('saisirCom', 'enregistrerCom');
        $actionsManager = array('voirStat');
        
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
                break;
            case 'seDeconnecter':
                $this->deconnexion();
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
        
        $co->executeQuery("SELECT * FROM manager WHERE nom = ? AND prenom = ?;", array( 1 => array($nom, PDO::PARAM_STR),
                                                                                        2 => array($prenom, PDO::PARAM_STR)
                                                                                      ));
        $result = $co->getResults();
        if(count($result) < 1 || $result[0]['nom']!=$nom || $result[0]['prenom']!=$prenom)
        {
            $co->executeQuery("SELECT * FROM serveur WHERE nom = ? AND prenom = ?;", array( 1 => array($nom, PDO::PARAM_STR),
                                                                                        2 => array($prenom, PDO::PARAM_STR)
                                                                                      ));
            if(count($result) < 1 || $result[0]['nom']!=$nom || $result[0]['prenom']!=$prenom)
            {
                throw new Exception("Login incorrect");
            }
            $_SESSION['login']=$result[0]['prenom'].'.'.$result[0]['nom'];
            $_SESSION['role']='serveur';
            $_SESSION['idRestau']=$result[0]['idRestaurant'];
            return;
        }
        $_SESSION['login']=$result[0]['prenom'].'.'.$result[0]['nom'];
        $_SESSION['role']='manager';
        $_SESSION['idRestau']=$result[0]['idRestaurant'];
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
        $co->executeQuery("SELECT idCarte FROM periodeCarte WHERE idRestaurant = ? AND dateDebut<NOW() AND dateFin>=NOW()", array(1 => array($_SESSION['idRestau'],PDO::PARAM_INT )));
        $result = $co->getResults();
        if(count($result)<1)
        {
            throw new Exception("Aucune carte en cours pour votre restaurant.");
        }
        $idCarte = $result[0][0];
        $co->executeQuery("SELECT idElement, nomMenu FROM menu WHERE idElement IN (SELECT idElement FROM prixElement WHERE idCarte=?)", array(1 => array($idCarte, PDO::PARAM_INT)));
        $menus = $co->getResults();
        $co->executeQuery("SELECT idElement, nomPlat FROM plat WHERE idElement IN (SELECT idElement FROM prixElement WHERE idCarte=?)", array(1 => array($idCarte, PDO::PARAM_INT)));
        $plats = $co->getResults();
        $co->executeQuery("SELECT idElement, nomBoisson FROM boissonOfferte WHERE idElement IN (SELECT idElement FROM prixElement WHERE idCarte=?)", array(1 => array($idCarte, PDO::PARAM_INT)));
        $boissons = $co->getResults();
        require("./src/view/saisieCommande.php");
    }
    
    private function enregistrerCommande()
    {
        var_dump($_REQUEST);
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

        $co->executeQuery("select plat.nomPlat,SUM(quantiteElement.quantite) AS somme FROM commande,quantiteElement,element,plat WHERE commande.idRestaurant=1 AND commande.idCommande=quantiteElement.idCommande AND quantiteElement.idElement=element.idElement AND element.idElement=plat.idElement GROUP BY nomPlat ORDER BY somme;");
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

		$query ="SELECT DISTINCT carte.idCarte,menu.idElement,menu.nomMenu,prixElement.prixElement
				FROM menu,prixElement,carte
					WHERE prixElement.idElement=menu.idElement;";
		$co->executeQuery($query);
        $menus = $co->getResults();

		$query ="SELECT plat.nomPlat,prixElement.prixElement
					FROM plat,prixElement,carte
						WHERE plat.idElement=prixElement.idElement
						AND Carte.idCarte=prixElement.idCarte
						AND prixElement.idCarte=1;";
		$co->executeQuery($query);
        $menus = $co->getResults();

        require("./src/view/cartes.php");
    }
    
}
