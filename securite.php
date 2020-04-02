<?php
 if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] === false){
    header('HTTP/1.0 403 Forbidden');
    echo "Accés refusé";
    die;
}
