<?php
session_start();

define('DB_HOST', 'localhost');
define('DB_NAME', 'appane_zangrando');
define('DB_USER', 'root');
define('DB_PASS', '');
define('SITE_URL', '/appane-backoffice');

try {
    $conn = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASS);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die('Errore di connessione al database.');
}
