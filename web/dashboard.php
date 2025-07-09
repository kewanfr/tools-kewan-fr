<?php
// public/dashboard.php

require_once './src/utils/db.php';
require_once './src/utils/functions.php';
require_once './src/utils/auth.php';

session_start();
requireLogin($pdo);

// Récupérer la liste des serveurs
$user_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM tools_servers");
$stmt->execute();
$tools_servers = $stmt->fetchAll();


?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tableau de Bord</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6 flex justify-between items-center">
            <h1 class="text-3xl font-bold text-blue-600">Tableau de Bord</h1>
            <div class="flex space-x-4">
                <span class="text-gray-700">Bienvenue, <?= htmlspecialchars($_SESSION['user_login']) ?></span>
                <a href="index" class="text-blue-500 hover:text-blue-700">Accueil</a>
                <a href="logout" class="text-blue-500 hover:text-blue-700">Déconnexion</a>
            </div>
        </div>
    </header>


    <main class="flex-grow container mx-auto px-4 py-8">
        <!-- Messages de Session -->
        <?php if (isset($_SESSION['error'])): ?>
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded">
                <?= htmlspecialchars($_SESSION['error']) ?>
            </div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                <?= htmlspecialchars($_SESSION['success']) ?>
            </div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <div class="bg-white shadow-md rounded-lg p-6 mb-8">
            <div class="flex space-x-4 mb-4">
                <button onclick="showTab('infra')" class="px-4 py-2 bg-blue-600 text-white rounded-md focus:outline-none">Infra</button>
                <button onclick="showTab('favoris')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md focus:outline-none">Favoris</button>
                <button onclick="showTab('authentification')" class="px-4 py-2 bg-gray-200 text-gray-800 rounded-md focus:outline-none">Authentification</button>
            </div>

            <div id="infra" class="tab-content">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Infrastructure</h2>
                <!-- Formulaire de Création de Serveur -->
                <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800 flex justify-between items-center cursor-pointer" onclick="toggleAccordion('serverForm')">
                        Créer un nouveau serveur
                        <span id="serverFormToggle" class="transform transition-transform">&#9660;</span>
                    </h2>
                    <div id="serverForm" class="hidden">
                        <form action="create_server.php" method="POST" class="space-y-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700">Nom du serveur:</label>
                                <input type="text" id="nom" name="nom" required placeholder="VM1"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="ip" class="block text-sm font-medium text-gray-700">Adresse IP (facultatif)</label>
                                <input type="text" id="ip" name="ip" pattern="[0-9]{0,3}.[0-9]{0,3}.[0-9]{0,3}.[0-9]{0,3}" title="0 à 3 caractères numériques 4 fois, séparés par des points"
                                    placeholder="192.168.0.151"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="hostname" class="block text-sm font-medium text-gray-700">Nom d'hote (facultatif) :</label>
                                <input type="text" id="hostname" name="hostname" placeholder="VM1.local"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description :</label>
                                <textarea id="description" name="description" rows="4" placeholder="Description du serveur"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>

                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700">Icone (facultatif) :</label>
                                <input type="text" id="icon" name="icon" placeholder="https://example.com/icon.png"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Ajouter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>


                <!-- Formulaire d'application -->
                <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800 flex justify-between items-center cursor-pointer" onclick="toggleAccordion('appForm')">
                        Créer une nouvelle application
                        <span id="appFormToggle" class="transform transition-transform">&#9660;</span>
                    </h2>
                    <div id="appForm" class="hidden">
                        <form action="create_app.php" method="POST" class="space-y-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700">Nom de l'application:</label>
                                <input type="text" id="nom" name="nom" required placeholder="Node.js"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="server_id" class="block text-sm font-medium text-gray-700">Serveur :</label>
                                <select id="server_id" name="server_id"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                                    <?php foreach ($tools_servers as $server): ?>
                                        <option value="<?= $server['id'] ?>"><?= $server['id'] ?> - <?= $server['nom'] ?> - <?= $server['ip'] ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="icon" class="block text-sm font-medium text-gray-700">Icon</label>
                                <input type="text" id="icon" name="icon"
                                    placeholder="./src/img/nodejs.svg"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="port" class="block text-sm font-medium text-gray-700">Port d'écoute :</label>
                                <input type="text" id="port" name="port" placeholder="VM1.local"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="description" class="block text-sm font-medium text-gray-700">Description :</label>
                                <textarea id="description" name="description" rows="4" placeholder="Description de l'application"
                                    class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"></textarea>
                            </div>

                            <div>
                                <button type="submit"
                                    class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                    Ajouter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Liste des serveurs -->
                <div class="bg-white shadow-md rounded-lg p-6">
                    <h2 class="text-2xl font-semibold mb-4 text-gray-800">Serveurs</h2>
                    <?php if (!empty($tools_servers)): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom D'hote</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Icone</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($tools_servers as $server): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <!-- <a target="_blank" class="text-blue-500 hover:text-blue-700"> -->
                                                <?= $server['nom'] ?>
                                                <!-- </a> -->
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <!-- <a target="_blank" class="text-blue-500 hover:text-blue-700"> -->
                                                <?= $server['ip'] ?>
                                                <!-- </a> -->
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <!-- <a target="_blank" class="text-blue-500 hover:text-blue-700"> -->
                                                <?= $server['hostname'] ?>
                                                <!-- </a> -->
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <!-- <a target="_blank" class="text-blue-500 hover:text-blue-700"> -->
                                                <?= $server['description'] ?>
                                                <!-- </a> -->
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <img src="<?= $server['icon'] ?>" alt="Icone" class="h-8 w-8 object-cover rounded-full">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="flex space-x-2">
                                                    <a href="edit_server.php?id=<?= $server['id'] ?>" class="text-yellow-500 hover:text-yellow-700">Éditer</a>
                                                    <a href="delete_server.php?id=<?= $server['id'] ?>" onclick="return confirm('Supprimer ce serveur ?')" class="text-red-500 hover:text-red-700">Supprimer</a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-600">Vous n'avez encore créé aucun serveur. Commencez dès maintenant !</p>
                    <?php endif; ?>
                </div>

            </div>

            <div id="favoris" class="tab-content hidden">
                <!-- Contenu de la partie Favoris -->
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Favoris</h2>
                <p class="text-gray-600">Cette section est en cours de développement.</p>
            </div>

            <div id="authentification" class="tab-content hidden">
                <!-- Contenu de la partie Authentification -->
                <h2 class="text-2xl font-semibold mb-4 text-gray-800">Authentification</h2>
                <p class="text-gray-600">Cette section est en cours de développement.</p>
            </div>
        </div>

        <script src="./src/js/dashboard.js"></script>



    </main>


    <?php include './src/php/footer.php'; ?>

</body>

</html>