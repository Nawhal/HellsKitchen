<html lang="fr">
    <head>
        <?php
            require_once './src/view/header.php';
        ?>
    </head>
    <body>
        <?php require_once './src/view/menu.php'; ?>
        <h1>Liste du personnel</h1>
        <?php
            foreach($employes as $row)
            {
                echo '<div><a href="?action=suppEmp&id='.$row['idEmploye'].'&type='.$row['type'].'"><button>Supprimer</button></a> - '.$row['nom'].' '.$row['prenom'].' - ('.$row['type'].')</div>';
            }
        ?>
    </body>
</html>