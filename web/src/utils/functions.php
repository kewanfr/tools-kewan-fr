<?php
// src/functions.php

include 'config.php';

function generateShortCode($length = 6) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $short_code = '';
    for ($i = 0; $i < $length; $i++) {
        $short_code .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $short_code;
}

function getBaseUrl() {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    $domain = $_SERVER['HTTP_HOST'];
    return $protocol . $domain;
}


function getServersList($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM tools_servers");
    $stmt->execute();

    $servers = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // for each server, associate all his associated apps
    foreach ($servers as &$server) {
        $stmt = $pdo->prepare("SELECT * FROM tools_server_apps WHERE server_id = :server_id");
        $stmt->execute(['server_id' => $server['id']]);
        $server['apps'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // for each server, associate all his associated links button
    foreach ($servers as &$server) {
        $stmt = $pdo->prepare("SELECT * FROM tools_server_links WHERE server_id = :server_id");
        $stmt->execute(['server_id' => $server['id']]);
        $server['links'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $servers;
}
