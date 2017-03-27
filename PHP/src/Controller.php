<?php
// Liste des employer : gestion du personnel supprimer /ajouter un gas
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
        $actionsClient = array('seConnecter', 'seDeconnecter','sansAction');
        $actionsServeur = array();
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
                                include './src/view/accueil.php';
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
            case 'sansAction':
                break;
            default:
                //erreur
        }
    }
    
    private function actionServeur($action)
    {
        
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
    
    public function seConnecter()
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
    
    public function deconnexion()
    {
        session_unset();
	session_destroy();
	$_SESSION = array();
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
	require('./view/vueStats.php');
     }
    
}