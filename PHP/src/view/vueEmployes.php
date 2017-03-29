<html lang="fr">
    <head>
        <?php
            require_once './src/view/header.php';
        ?>
    </head>
    <body>
        <?php require_once './src/view/menu.php'; ?>
        <h2>Ajout</h2>
        <form action="" method="get">
            <input name="action" type="hidden" value="addEmp">
            <input type="submit" value="Ajouter">
            <input name="nom" type="text" placeholder="Nom" size="15">
            <input name="prenom" type="text" placeholder="Prenom" size="15">
            <input name="datenais" type="text" placeholder="Naissance : jj/mm/aaaa" size="15">
            <select name="type">
                <option value="serveur" selected>Serveur</option>
                <option value="cuisinier">Cuisinier</option>
                <option value="manager">Manager</option>
            </select>
            <input name="spec" type="text" placeholder="Spécialité" id="spec" size="15">
            <input name="okaccueil" value="ok" type="checkbox" id="okaccueil"><label for="okaccueil">Autorisé a l'accueil</label>
        </form>
        <hr><br>
        <h2>Liste du personnel</h2>
        <?php
            foreach($employes as $row)
            {
                if($_SESSION['login'] == $row['prenom'].'.'.$row['nom'])
                        continue;
                echo '<div><a href="?action=suppEmp&id='.$row['idemploye'].'&type='.$row['type'].'"><button>Supprimer</button></a> - '.$row['nom'].' '.$row['prenom'].' - ('.$row['type'].')</div>';
            }
        ?>
    </body>
</html>