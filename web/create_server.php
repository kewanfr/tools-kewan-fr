<?php
// public/create_link.php

require_once './src/utils/db.php';
require_once './src/utils/functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {


    $nom = isset($_POST['nom']) ? $_POST['nom'] : null;
    $ip = isset($_POST['ip']) ? $_POST['ip'] : null;
    $hostname = isset($_POST['hostname']) ? $_POST['hostname'] : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $icon = isset($_POST['icon']) ? trim($_POST['icon']) : null;

    if ($icon != null && !(str_starts_with($icon, './') || str_starts_with($icon, 'http'))) {
        $icon = "./src/img/" . $icon;
    }

    if (!str_ends_with($icon, '.svg') && str_starts_with($icon, './')) {
        $icon = $icon . ".svg";
    }

    // Insérer le lien dans la base de données
    $stmt = $pdo->prepare("INSERT INTO tools_servers (nom, ip, hostname, description, icon) VALUES (:nom, :ip, :hostname, :description, :icon)");
    $stmt->execute([
        'nom'   => $nom,
        'ip' => $ip,
        'hostname'      => $hostname,
        'description'    => $description,
        'icon'   => $icon
    ]);

    $_SESSION['nom'] = $nom;

    header("Location: /");
    exit();
} else {
    header("Location: /");
    exit();
}
?>
