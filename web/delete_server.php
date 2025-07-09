<?php
// public/delete_link.php

require_once './src/utils/db.php';
require_once './src/utils/auth.php';

session_start();
requireLogin($pdo);

if (isset($_GET['id'])) {
    $server_id = intval($_GET['id']);

    // Vérifier que le lien appartient à l'utilisateur
    $stmt = $pdo->prepare("SELECT * FROM tools_servers WHERE id = :id");
    $stmt->execute(['id' => $server_id]);
    $link = $stmt->fetch();

    if ($link) {
        // Supprimer le lien
        $stmt = $pdo->prepare("DELETE FROM tools_servers WHERE id = :id");
        $stmt->execute(['id' => $server_id]);

        $_SESSION['success'] = "Serveur supprimé avec succès.";
    } else {
        $_SESSION['error'] = "Serveur introuvable ou vous n'avez pas la permission de le supprimer.";
    }
}

header("Location: dashboard.php");
exit();
?>
