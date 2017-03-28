<div style="margin: auto; padding:1em; background-color: #FFF3F3;">
    <form action="" method="get">
        <input type="hidden" name="action" value="sansAction">
        <input type="submit" value="Accueil">
    </form>
    <?php if(empty($_SESSION['role']) && empty($_SESSION['login'])) { ?>
            <form action="" method="get">
                Login : 
                <input type="text" name="login">
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
