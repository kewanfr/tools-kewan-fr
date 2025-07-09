<?php
// public/register.php

require_once './src/utils/db.php';
require_once './src/utils/functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $login = $_POST['login'];
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (!$login) {
        $_SESSION['error'] = "Login invalide.";
        header("Location: register.php");
        exit();
    }

    // if (strlen($password) < 6) {
    //     $_SESSION['error'] = "Le mot de passe doit contenir au moins 6 caractères.";
    //     header("Location: register.php");
    //     exit();
    // }

    if ($password !== $password_confirm) {
        $_SESSION['error'] = "Les mots de passe ne correspondent pas.";
        header("Location: register.php");
        exit();
    }

    // Vérifier si l'email existe déjà
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE login = :login");
    $stmt->execute(['login' => $login]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error'] = "Ce login est déjà utilisé.";
        header("Location: register.php");
        exit();
    }

    // Insérer l'utilisateur dans la base de données
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $stmt = $pdo->prepare("INSERT INTO users (login, password) VALUES (:login, :password)");
    $stmt->execute([
        'login' => $login,
        'password' => $hashed_password
    ]);

    $_SESSION['success'] = "Inscription réussie. Veuillez vous connecter.";
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold text-center text-blue-600">Inscription</h1>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-4 py-8">
        <div class="max-w-md mx-auto bg-white shadow-md rounded-lg p-6">
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

            <form action="register.php" method="POST" class="space-y-6">
                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700">Nom D'utilisateur :</label>
                    <input type="text" id="login" name="login" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="pseudo">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe :</label>
                    <input type="password" id="password" name="password" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="••••••••">
                </div>

                <div>
                    <label for="password_confirm" class="block text-sm font-medium text-gray-700">Confirmer le mot de passe :</label>
                    <input type="password" id="password_confirm" name="password_confirm" required
                        class="mt-1 block w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500"
                        placeholder="••••••••">
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        S'inscrire
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Déjà inscrit ? <a href="login.php" class="font-medium text-blue-600 hover:text-blue-500">Connexion</a>
            </p>
        </div>
    </main>

    <?php include './src/php/footer.php'; ?>

</body>

</html>