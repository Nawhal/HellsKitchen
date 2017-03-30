<div style="margin: auto; padding:1em; background-color: #FFF3F3;">
    <form action="" method="get">
        <button name="action" value="sansAction" type="submit">Accueil</button>
        <?php if(!empty($_SESSION['role']) && $_SESSION['role']=='serveur') { ?>
                <button name="action" value="saisirCom" type="submit">Saisir une commande</button>
        <?php } if(!empty($_SESSION['role']) && $_SESSION['role']=='manager') {?>
                <button name="action" value="voirStat" type="submit">Statistiques</button>
                <button name="action" value="voirEmp" type="submit">Gestion du Personnel</button>
                <button name="action" value="gestionCartes" type="submit">Gestion des Cartes</button>
        <?php } ?>
    </form>
    <?php if(empty($_SESSION['role']) && empty($_SESSION['login'])) { ?>
            <form action="" method="get">
                Login : 
                <input type="text" name="login" placeholder="Prenom.Nom">
                <input type="hidden" name="action" value="seConnecter">
                <input type="submit" value="Connexion">
            </form>
    <?php  } else { ?>
            <form action="" method="get">
                <?php echo '<strong>'.$_SESSION['role'].'</strong> - '.$_SESSION['login'] ?>
                <input type="hidden" name="action" value="seDeconnecter">
                <input type="submit" value="Deconnexion">
            </form>
    <?php } ?>
</div>
