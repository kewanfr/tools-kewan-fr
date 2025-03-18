
<?php
// index.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ini_set('display_errors', 0);
// ini_set('display_startup_errors', 0);
// error_reporting(0);

define('BASE_PATH', __DIR__ . '/../');

require_once  './src/utils/db.php';
require_once './src/utils/functions.php';

session_start();

$url = isset($_SERVER['REQUEST_URI']) ? trim($_SERVER['REQUEST_URI'], '/') : '';

// Si pas connecté, rediriger vers la page de connexion
// if ((!isset($_SESSION['user_id']) || (!isset($_SESSION['user_admin']) && $_SESSION['user_admin']) != 1) && !in_array($url, ['login', 'register', 'forgot-password'])) {
//     header('Location: login.php');
//     exit();
// }

if ($url === '' || $url === '/' || $url == 'index.php' || $url == 'index' || $url == 'home') {
    // Afficher la page d'accueil
    require 'home.php';
    exit();
}
?>
