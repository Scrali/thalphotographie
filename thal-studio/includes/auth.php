<?php
require_once __DIR__ . '/../config.php';

function thal_session_start(): void {
    if (session_status() !== PHP_SESSION_ACTIVE) {
        $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off');
        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'domain' => '',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
        session_start();
    }

    // Déconnexion automatique après 2 heures d'inactivité
    if (!empty($_SESSION['thal_last_activity']) && time() - $_SESSION['thal_last_activity'] > 7200) {
        logout_user();
        header('Location: login.php');
        exit;
    }

    $_SESSION['thal_last_activity'] = time();
}

function is_logged_in(): bool {
    thal_session_start();
    return !empty($_SESSION['thal_logged_in']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

function login_user(string $username, string $password): bool {
    thal_session_start();

    if (hash_equals(THAL_USER, $username) && password_verify($password, THAL_PASSWORD_HASH)) {
        session_regenerate_id(true);
        $_SESSION['thal_logged_in'] = true;
        $_SESSION['thal_user'] = $username;
        return true;
    }

    return false;
}

function logout_user(): void {
    thal_session_start();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
    }
    session_destroy();
}

function csrf_token(): string {
    thal_session_start();
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_check(?string $token): bool {
    thal_session_start();
    return is_string($token) && !empty($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
}
