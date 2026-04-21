<?php
// router.php : Script de routage pour le serveur interne de PHP (utilisé par Electron)
// Permet de forcer les bons types MIME sur Windows qui pose souvent problème avec php -S

$path = parse_url($_SERVER["REQUEST_URI"], PHP_URL_PATH);
$filePath = $_SERVER["DOCUMENT_ROOT"] . $path;

if (file_exists($filePath) && is_file($filePath)) {
    $ext = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    
    $mimeTypes = [
        'css' => 'text/css',
        'js' => 'application/javascript',
        'svg' => 'image/svg+xml',
        'png' => 'image/png',
        'jpg' => 'image/jpeg',
        'jpeg' => 'image/jpeg',
        'woff' => 'font/woff',
        'woff2' => 'font/woff2'
    ];

    if (isset($mimeTypes[$ext])) {
        header("Content-Type: " . $mimeTypes[$ext]);
        readfile($filePath);
        return true;
    }
    
    return false; // Laisse php -S gérer les autres fichiers
}

// Sinon, on passe au routeur frontal de Symfony
$_SERVER['SCRIPT_NAME'] = '/index.php';
$_SERVER['SCRIPT_FILENAME'] = $_SERVER["DOCUMENT_ROOT"] . '/index.php';
require $_SERVER['SCRIPT_FILENAME'];
