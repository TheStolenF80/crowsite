<?php require_once __DIR__ . '/includes/config.php'; ?><?php require_once __DIR__ . '/includes/auth.php';
$login_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['login'] ?? '');
  $password = trim($_POST['password'] ?? '');
  if (mb_strlen($login) < 3) {
    $login_msg = 'Логин должен содержать минимум 3 символа';
  } elseif (mb_strlen($password) < 6) {
    $login_msg = 'Пароль должен содержать минимум 6 символов';
  } elseif (!verify_user($login, $password)) {
    $login_msg = 'Неверный логин или пароль';
  } else {
    login_user($login);
    header('Location: profile.php');
    exit;
  }
} ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CROW — Авторизация</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">

    <div class="main-container">
      <div class="background-text">CROW</div>
      <header class="header">
        <img class="logo" src="img/crow.png" alt="Crow Logo">
        <nav class="navigation">
          <button class="nav-button" data-target="#home" data-page="home">Главная</button>
          <button class="nav-button" data-target="#goods" data-page="goods">Товары</button>
          <button class="nav-button" data-target="#support" data-page="support">Поддержка</button>
          <button class="nav-button" data-target="#contacts" data-page="contacts">Контакты</button>
        <?php if (!empty($_SESSION['is_admin'])): ?>

            <button class="nav-button" onclick="window.location.href='admin.php'">&#1040;&#1076;&#1084;&#1080;&#1085;&#1082;&#1072;</button>

        <?php endif; ?>

        </nav>
        <?php if (isset($_SESSION['user'])): ?>
<div class="user-account" onclick="window.location.href='profile.php'">
  Личный кабинет (<?php echo htmlspecialchars($_SESSION['user']); ?>)
</div>
<?php else: ?>
<div class="user-account" onclick="window.location.href='login.php'">
  Личный кабинет
</div>
<?php endif; ?>
      </header>
      <div class="auth-container">
        <div class="auth-card">
          <div class="glow-effect"></div>
          <div class="auth-content">
            <h2 class="auth-title">Авторизация</h2>
            <form class="auth-form" id="authForm" method="POST" action="login.php">
              <div class="input-group">
                <input type="text" class="auth-input" placeholder="Логин" id="login" required name="login">
              </div>
              <div class="input-group">
                <input type="password" class="auth-input" placeholder="Пароль" id="password" required name="password">
              </div>
              <button type="submit" class="submit-button">Войти</button>
              <?php if (!empty($login_msg)): ?><div class="form-message error" id="loginMessage" aria-live="polite"><?php echo htmlspecialchars($login_msg); ?></div><?php else: ?><div class="form-message" id="loginMessage" aria-live="polite"></div><?php endif; ?>
            </form>
          </div>
        </div>
        <div class="register-row">
          <a href="register.php" class="register-link" id="registerBtn">Регистрация</a>
        </div>
      </div>
      <img src="img/light.png" alt="Background" class="bg-image bg-image-1">
      <img src="img/purple5.png" alt="Background" class="bg-image bg-image-2">
      <img src="img/purple4.png" alt="Background" class="bg-image bg-image-3">
    </div>
    <script src="script.js"></script>
  
<script>
  window.IS_AUTH = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>
<script src="script.js"></script>
</body>
</html>
