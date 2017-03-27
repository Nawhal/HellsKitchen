<?php


class Controller
{
    public function __construct()
    {
        $actionsClient = array('seConnecter', 'seDeconnecter','sansAction');
        $actionsManager = array('test');
        
        session_start();
        
        try
        {
                $action = isset($_REQUEST['action']) ? $_REQUEST['action']:'sansAction';
                switch($action)
                {
                        case in_array($action, $actionsClient):
                                $this->actionClient($action);
                                break;
                        case in_array($action, $actionsManager):
                                if($this->isManager($action))
                                {
                                    $this->actionManager();
                                    break;
                                }
                                //Erreur Droit insuffisant
                                break;
                        default:
                                //En cas d'action inconnue : Page accueil / Page erreur?
                }
        }
        catch(PDOException $e)
        {
            echo "<p> ERREUR BDD <br>".$e->getMessage()."</p>";
            die();
        }
        catch(Exception $e)
        {
            echo "<p>".$e->getMessage()."</p>";
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
				$this->afficherAccueil();
                break;
            default:
                //erreur
        }
    }
    
    
    private function actionManager($action)
    {
        
    }
    
    
    private function isManager()
    {
        if(isset($_SESSION['login']) && isset ($_SESSION['role']) && $_SESSION['role']=='manager')
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
            throw new Exception("Login incorrect");
        }
        $_SESSION['login']=$result[0]['prenom'].'.'.$result[0]['nom'];
        $_SESSION['role']='manager';
    }
    
    public function deconnexion()
    {
        session_unset();
		session_destroy();
		$_SESSION = array();
    }

	private function afficherAccueil()
	{
		$dbInfos = Config::getDataBaseInfos();
		$query ="SELECT * FROM restaurant;";
		$co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
		$co->executeQuery($query);
		$res = $co->getResults();
		include "./src/view/accueil.php";
	}

	private function afficherCartes()
	{
		$dbInfos = Config::getDataBaseInfos();
		$query ="SELECT * FROM restaurant;";
		$co = new Connection($dbInfos['dbName'], $dbInfos['login'], $dbInfos['mdp']);
		$co->executeQuery($query);
		$res = $co->getResults();
		include "./src/view/cartes.php";
	}
}
