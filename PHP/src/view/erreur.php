<html lang="fr">
    <head>
        <?php
            require_once './src/view/header.php';
        ?>
    </head>
    <body>
        <?php require_once './src/view/menu.php'; ?>
            <h1><?php echo $errTitle; ?></h1>
			<?php echo $e->getMessage(); ?>
    </body>
</html>