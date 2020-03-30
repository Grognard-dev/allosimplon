<?php
session_start();
unset($_SESSION['Pseudo']);
unset($_SESSION['ID']);
session_destroy();
header('Location: /allosimplon/connexion.php');