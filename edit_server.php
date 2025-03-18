<?php
// public/edit_link.php

require_once './src/utils/db.php';
require_once './src/utils/functions.php';
require_once './src/utils/auth.php';

session_start();
requireLogin($pdo);

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$server_id = intval($_GET['id']);

// var_dump($server_id);

// Récupérer le lien
$stmt = $pdo->prepare("SELECT * FROM tools_servers WHERE id = :id");
$stmt->execute(['id' => $server_id]);
$server = $stmt->fetch();

$stmt2 = $pdo->prepare("SELECT admin FROM users WHERE id = :id");
$stmt2->execute(['id' => $user_id]);
$admin = $stmt2->fetch();

// die(var_dump($admin));

if ($admin['admin'] == 1) {
    $stmt = $pdo->prepare("SELECT * FROM tools_servers WHERE id = :id");
    $stmt->execute(['id' => $server_id]);
    $server = $stmt->fetch();

}

if (!$server) {
    $_SESSION['error'] = "Serveur introuvable ou vous n'avez pas la permission de l'éditer.";
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nom = isset($_POST['nom']) ? trim($_POST['nom']) : null;
    $ip = isset($_POST['ip']) ? trim($_POST['ip']) : null;
    $hostname = isset($_POST['hostname']) ? trim($_POST['hostname']) : null;
    $description = isset($_POST['description']) ? trim($_POST['description']) : null;
    $icon = isset($_POST['icon']) ? trim($_POST['icon']) : null;


    if ($icon != null && !(str_starts_with($icon, './') || str_starts_with($icon, 'http'))) {
        $icon = "./src/img/" . $icon;
    }

    if (!str_ends_with($icon, '.svg')) {
        $icon = $icon . ".svg";
    }

    // Mettre à jour le lien dans la base de données
    $stmt = $pdo->prepare("UPDATE tools_servers SET nom = :nom, ip = :ip, hostname = :hostname, description = :description, icon = :icon WHERE id = :id");
    $stmt->execute([
        'nom' => $nom,
        'ip'   => $ip,
        'hostname'    => $hostname,
        'description'   => $description,
        'icon'   => $icon,
        'id'           => $server_id
    ]);
    $_SESSION['success'] = "Serveur mis à jour avec succès.";
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Éditer le Serveur</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold text-center text-blue-600">Éditer le Serveur</h1>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-lg mx-auto bg-white shadow-md rounded-lg p-6">
            <?php if (isset($_SESSION['error'])): ?>
                <div class="mb-4 p-3 bg-red-100 border border-red-400 text-red-700 rounded">
                    <?= htmlspecialchars($_SESSION['error']) ?>
                </div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="mb-4 p-3 bg-green-100 border border-green-400 text-green-700 rounded">
                    <?= htmlspecialchars($_SESSION['success']) ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <form action="edit_server.php?id=<?= $server_id ?>" method="POST" class="space-y-6">

                <div>
                    <label for="nom" class="block text-sm font-medium text-gray-700">Nom du serveur:</label>
                    <input type="text" id="nom" name="nom" required value="<?= htmlspecialchars($server['nom']) ?>" placeholder="VM1"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="ip" class="block text-sm font-medium text-gray-700">Adresse IP:</label>
                    <input type="text" id="ip" name="ip" required value="<?= htmlspecialchars($server['ip']) ?>" placeholder="Adresse IP"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>

                <div>
                    <label for="hostname" class="block text-sm font-medium text-gray-700">Nom d'hote (facultatif) :</label>
                    <input type="text" id="hostname" name="hostname" value="<?= htmlspecialchars($server['hostname']) ?>" placeholder="VM1.local"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>


                <div>
                    <label for="description" class="block text-sm font-medium text-gray-700">Description :</label>
                    <!-- <input type="text" id="description" name="description" placeholder="VM1.local" -->
                    <!-- class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"> -->
                    <textarea id="description" name="description" rows="4" placeholder="Description du serveur"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"><?= htmlspecialchars($server['description']) ?></textarea>
                </div>


                <div>
                    <label for="icon" class="block text-sm font-medium text-gray-700">Icone (facultatif) :</label>
                    <input type="text" id="icon" name="icon" value="<?= htmlspecialchars($server['icon']) ?>" placeholder="https://example.com/icon.png"
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                </div>


                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Mettre à jour
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                <a href="dashboard.php" class="font-medium text-blue-600 hover:text-blue-500">Retour au Tableau de Bord</a>
            </p>
        </div>
    </main>

    <?php include './src/php/footer.php'; ?>

</body>

</html>