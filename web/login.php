<?php
// public/login.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once './src/utils/db.php';
require_once './src/utils/functions.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (!$login || !$password) {
        $_SESSION['error'] = "Login et mot de passe requis.";
        header("Location: login.php");
        exit();
    }

    // Récupérer l'utilisateur depuis la base de données
    $stmt = $pdo->prepare("SELECT * FROM users WHERE login = :login");
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        // Authentification réussie
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_login'] = $user['login'];
        $_SESSION['user_admin'] = $user['admin'];

        // cookies
        setcookie('user_id', $user['id'], time() + (86400 * 30), "/"); // 86400 = 1 jour
        setcookie('user_login', $user['login'], time() + (86400 * 30), "/");
        setcookie('user_admin', $user['admin'], time() + (86400 * 30), "/");

        // // jwt cookie
        // $jwt = generateJWT($user['id'], $user['login'], $user['admin']);
        // setcookie('jwt', $jwt, time() + (86400 * 30), "/"); // 86400 = 1 jour


        header("Location: dashboard.php");
        exit();
    } else {
        $_SESSION['error'] = "Login ou mot de passe incorrect.";
        header("Location: login.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-6">
            <h1 class="text-3xl font-bold text-center text-blue-600">Connexion</h1>
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

            <form action="login.php" method="POST" class="space-y-6">
                <div>
                    <label for="login" class="block text-sm font-medium text-gray-700">Nom d'utilisateur :</label>
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

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember_me" type="checkbox"
                            class="h-4 w-4 text-blue-600 focus:ring-blue-500 border-gray-300 rounded">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-900">
                            Se souvenir de moi
                        </label>
                    </div>

                    <!-- <div class="text-sm">
                        <a href="forgot_password.php" class="font-medium text-blue-600 hover:text-blue-500">
                            Mot de passe oublié ?
                        </a>
                    </div> -->
                </div>

                <div>
                    <button type="submit"
                        class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Se connecter
                    </button>
                </div>
            </form>

            <p class="mt-6 text-center text-sm text-gray-600">
                Pas encore inscrit ? <a href="register.php" class="font-medium text-blue-600 hover:text-blue-500">Inscription</a>
            </p>
        </div>
    </main>

    <?php include './src/php/footer.php'; ?>
</body>

</html>