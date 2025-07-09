<?php
// public/home.php

require_once './src/utils/db.php';
require_once './src/utils/functions.php';

// session_start();

$servers = getServersList($pdo);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Tools Kéwan.fr</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold text-center text-blue-600">Tools Kéwan.fr</h1>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8">
        <?php if (isset($_SESSION['user_id'])): ?>
            <div class="bg-white shadow-md rounded-lg p-6 mb-6 text-center">
                <p class="text-lg">Bienvenue, <?= htmlspecialchars($_SESSION['user_login']) ?>!</p>
                <div class="mt-4">
                    <a href="dashboard.php" class="text-blue-500 hover:text-blue-700 mx-2">Tableau de bord</a>
                    <span class="text-gray-400">|</span>
                    <a href="logout" class="text-blue-500 hover:text-blue-700 mx-2">Déconnexion</a>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white shadow-md rounded-lg p-6 mb-6 text-center">
                <a href="login.php" class="text-blue-500 hover:text-blue-700 mx-2">Connexion</a>
                <span class="text-gray-400">|</span>
                <a href="register.php" class="text-blue-500 hover:text-blue-700 mx-2">Inscription</a>
            </div>
        <?php endif; ?>

        <div class="mt-10">
            <h2 class="text-2xl font-semibold mb-4 text-center text-gray-800">Serveurs</h2>
            <?php if (!empty($servers)): ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                    <?php foreach ($servers as $server): ?>
                        <div class="bg-white shadow-lg p-6 rounded-lg flex flex-col items-center space-y-4">
                            <?php if (!empty($server['icon'])): ?>
                                <img src="<?= htmlspecialchars($server['icon']) ?>" alt="Icon" class="w-16 h-16 rounded-full">
                            <?php endif; ?>
                            <div class="text-center">
                                <?php if (!empty($server['nom'])): ?>
                                    <h3 class="text-xl font-semibold text-gray-800"><?= htmlspecialchars($server['nom']) ?></h3>
                                <?php endif; ?>
                                <?php if (!empty($server['ip'])): ?>
                                    <p class="text-sm text-gray-600">IP: <?= htmlspecialchars($server['ip']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($server['hostname'])): ?>
                                    <p class="text-sm text-gray-600">Hostname: <?= htmlspecialchars($server['hostname']) ?></p>
                                <?php endif; ?>
                                <?php if (!empty($server['description'])): ?>
                                    <p class="text-sm text-gray-600 mt-2"><?= htmlspecialchars($server['description']) ?></p>
                                <?php endif; ?>
                            </div>
                            <?php if (!empty($server['apps'])): ?>
                                <div class="flex flex-wrap justify-center space-x-2">
                                    <?php foreach ($server['apps'] as $app): ?>
                                        <img src="<?= htmlspecialchars($app['icon']) ?>" alt="<?= htmlspecialchars($app['nom']) ?>" title="<?= htmlspecialchars($app['nom']) ?>" class="w-6 h-6">
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            <?php if (!empty($server['links'])): ?>
                                <div class="flex flex-wrap justify-center space-x-2 mt-4">
                                    <?php foreach ($server['links'] as $link): ?>
                                        <a href="<?= htmlspecialchars($link['url']) ?>" target="_blank" class="px-3 py-2 bg-blue-500 text-white text-sm rounded hover:bg-blue-600 flex items-center space-x-2">
                                            <?php if (!empty($link['icon'])): ?>
                                                <img src="<?= htmlspecialchars($link['icon']) ?>" alt="" class="w-4 h-4">
                                            <?php endif; ?>
                                            <span><?= htmlspecialchars($link['nom']) ?></span>
                                        </a>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <p class="text-center text-gray-600">Aucun serveur disponible.</p>
            <?php endif; ?>
        </div>
        </div>
        <div class="mt-10">
            <!-- Formulaire d'application -->
            <div class="bg-white shadow-md rounded-lg p-6 mb-8">
                <h2 class="text-2xl font-semibold mb-4 text-gray-800 flex justify-between items-center cursor-pointer" onclick="toggleAccordion('hourCalc')">
                    Calculateur d'heures de travail
                    <span id="hourCalcToggle" class="transform transition-transform">&#9660;</span>
                </h2>
                <div id="hourCalc">



                    <!-- Catégorie MATIN -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <h3 class="text-lg font-semibold text-gray-800">MATIN</h3>
                                <input type="checkbox" id="matinEnabled" name="matinEnabled" class="form-checkbox h-5 w-5 text-blue-600">
                                <label for="matinEnabled" class="text-sm text-gray-700">Prendre en compte</label>
                            </div>
                            <div class="text-sm text-gray-500">
                                Heures du matin : <span id="matinValidatedHours">00:00</span>
                            </div>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <!-- Heure d'entrée -->
                            <div>
                                <label for="startTime" class="block text-sm font-medium text-gray-700">Heure d'entrée</label>
                                <input type="time" id="startTime" name="startTime" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <!-- Heure début de pause -->
                            <div>
                                <label for="breakStartTime" class="block text-sm font-medium text-gray-700">Heure début de pause</label>
                                <input type="time" id="breakStartTime" name="breakStartTime"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Séparateur visuel -->
                    <hr class="my-6 border-t-2 border-gray-200">

                    <!-- Catégorie APRÈS-MIDI -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center space-x-2">
                                <h3 class="text-lg font-semibold text-gray-800">APRÈS-MIDI</h3>
                                <input type="checkbox" id="apresMidiEnabled" name="apresMidiEnabled" class="form-checkbox h-5 w-5 text-blue-600">
                                <label for="apresMidiEnabled" class="text-sm text-gray-700">Prendre en compte</label>
                            </div>
                            <div class="text-sm text-gray-500">
                                Heures de l'après midi : <span id="apresMidiValidatedHours">00:00</span>
                            </div>
                        </div>
                        <div>
                            <label for="breakEndTime" class="block text-sm font-medium text-gray-700">Heure fin de pause</label>
                            <input type="time" id="breakEndTime" name="breakEndTime"
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                        </div>
                    </div>

                    <!-- Séparateur visuel -->
                    <hr class="my-6 border-t-2 border-gray-200">

                    <!-- Temps à faire -->
                    <div class="mb-4">
                        <label for="workDuration" class="block text-sm font-medium text-gray-700">Temps à faire</label>
                        <input type="time" id="workDuration" name="workDuration"
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500">
                    </div>


                    <!-- heure à laquelle terminer: Résultat du calcul (en grand, pas un input mais un texte) -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Heure à laquelle terminer</label>
                        <div class="flex items-end space-x-3">
                            <p id="resultTime" class="mt-1 text-2xl font-semibold text-blue-600">00:00</p>
                            <span id="pauseDuration" class="mt-1 text-sm ml-2 text-gray-500">(pause: 00:00)</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="./src/js/home.js"></script>
    </main>

    <?php include './src/php/footer.php'; ?>
    <!-- /<script src="./src/js/upload.js"></script> -->
</body>

</html>