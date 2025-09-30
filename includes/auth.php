<?php
require_once __DIR__ . '/config.php';

function find_user_by_login($login) {
    $sql = "SELECT * FROM users WHERE LOWER(login) = LOWER(?) LIMIT 1";
    $stmt = db()->prepare($sql);
    $stmt->execute([$login]);
    return $stmt->fetch();
}

function find_user_by_email($email) {
    $sql = "SELECT * FROM users WHERE LOWER(email) = LOWER(?) LIMIT 1";
    $stmt = db()->prepare($sql);
    $stmt->execute([$email]);
    return $stmt->fetch();
}

function find_user_by_id($id) {
    $sql = "SELECT * FROM users WHERE id = ? LIMIT 1";
    $stmt = db()->prepare($sql);
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function create_user($login, $email, $password) {
    $login = trim($login);
    $email = trim($email);
    $password = trim($password);

    if (mb_strlen($login) < 3) {
        return ['ok' => false, 'msg' => 'Логин должен содержать минимум 3 символа.'];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ['ok' => false, 'msg' => 'Введите корректный e-mail.'];
    }

    if (mb_strlen($password) < 6) {
        return ['ok' => false, 'msg' => 'Пароль должен содержать минимум 6 символов.'];
    }

    if (find_user_by_login($login)) {
        return ['ok' => false, 'msg' => 'Пользователь с таким логином уже существует.'];
    }

    if (find_user_by_email($email)) {
        return ['ok' => false, 'msg' => 'Пользователь с таким e-mail уже зарегистрирован.'];
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $pdo = db();
        $isFirstUser = (int)$pdo->query("SELECT COUNT(*) FROM users")->fetchColumn() === 0;
        $stmt = $pdo->prepare("INSERT INTO users (login, email, pass, is_admin) VALUES (?, ?, ?, ?)");
        $stmt->execute([$login, $email, $hash, $isFirstUser ? 1 : 0]);
    } catch (Throwable $e) {
        return ['ok' => false, 'msg' => 'Не удалось сохранить пользователя. Попробуйте позже.'];
    }

    return ['ok' => true];
}

function verify_user($login, $password) {
    return authenticate_user($login, $password) !== null;
}

function authenticate_user($login, $password) {
    $user = find_user_by_login($login);
    if (!$user) {
        return null;
    }

    if (!password_verify($password, $user['pass'])) {
        return null;
    }

    return $user;
}

function current_user_login() {
    return $_SESSION['user'] ?? null;
}

function current_user() {
    return current_user_login();
}

function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

function current_user_row() {
    $userId = current_user_id();
    if ($userId) {
        return find_user_by_id($userId);
    }

    $login = current_user_login();
    return $login ? find_user_by_login($login) : null;
}

function current_user_is_admin() {
    return !empty($_SESSION['is_admin']);
}

function require_login() {
    if (!current_user_login()) {
        header('Location: login.php');
        exit;
    }
}

function require_admin() {
    require_login();
    if (!current_user_is_admin()) {
        header('Location: index.php');
        exit;
    }
}

function login_user($user) {
    if (is_array($user)) {
        $_SESSION['user'] = $user['login'];
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['is_admin'] = !empty($user['is_admin']);
        return;
    }

    $row = find_user_by_login($user);
    if ($row) {
        login_user($row);
    }
}

function logout_user() {
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params['path'], $params['domain'],
            $params['secure'], $params['httponly']
        );
    }
    session_destroy();
}
?>
