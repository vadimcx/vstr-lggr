<?php
session_start();

$config = require '../config.php';

define('SESSION_TIMEOUT', 600);
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOGIN_WINDOW', 300);

if (isset($_SESSION['last_activity']) && time() - $_SESSION['last_activity'] > SESSION_TIMEOUT) {
    session_unset();
    session_destroy();
    header('Location: login.php?timeout=1');
    exit;
}
$_SESSION['last_activity'] = time();

function isLoggedIn(): bool {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function requireLogin(): void {
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function generateCsrfToken(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
} 

function verifyCsrfToken($token): bool {
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function attemptLogin(string $user, string $pass): bool {
    global $config;

    if (!isset($_SESSION['login_attempts'])) {
        $_SESSION['login_attempts'] = [];
    }

    $_SESSION['login_attempts'] = array_filter($_SESSION['login_attempts'], fn($t) => $t >= time() - LOGIN_WINDOW);

    if (count($_SESSION['login_attempts']) >= MAX_LOGIN_ATTEMPTS) {
        return false;
    }

    if ($user === $config['admin_user'] && password_verify($pass, $config['admin_pass_hash'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['login_attempts'] = [];
        return true;
    }

    $_SESSION['login_attempts'][] = time();
    return false;
}

function logout(): void {
    session_unset();
    session_destroy();
}
