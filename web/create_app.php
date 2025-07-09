<?php
// public/create_app.php

require_once './src/utils/db.php';
require_once './src/utils/functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $server_id = isset($_POST['server_id']) ? $_POST['server_id'] : null;
    $stmt = $pdo->prepare("SELECT * FROM tools_servers WHERE id = :id");
    $stmt->execute(['id' => $server_id]);
    $server = $stmt->fetch();
    if (!$server) {
        $_SESSION['error'] = "Serveur introuvable.";
        header("Location: /");
        exit();
    }
    
    $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
    $port = isset($_POST['port']) ? $_POST['port'] : null;
    $icon = isset($_POST['icon']) ? trim($_POST['icon']) : null;

    if ($icon != null && !(str_starts_with($icon, './') || str_starts_with($icon, 'http'))) {
        $icon = "./src/img/" . $icon;
    }

    if (!str_ends_with($icon, '.svg') && str_starts_with($icon, './')) {
        $icon = $icon . ".svg";
    }

    // Insérer l'app dans la base de données
    $stmt = $pdo->prepare("INSERT INTO tools_server_apps (server_id, nom, port, icon) VALUES (:server_id, :nom, :port,:icon)");
    $stmt->execute([
        'server_id' => $server_id,
        'nom'   => $nom,
        'port' => $port,
        'icon'   => $icon
    ]);


    header("Location: /");
    exit();
} else {
    header("Location: /");
    exit();
}
?>
