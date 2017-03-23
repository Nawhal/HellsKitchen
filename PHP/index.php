<?php

    require_once('./src/Config.php');
    require_once('./src/Controller.php');
    require_once('./src/Connection.php');
    try
    {
        new Controller();
    }
    catch (Exception $e)
    {
        echo "<p>".$e->getMessage()."</p>";
        die();
    }
