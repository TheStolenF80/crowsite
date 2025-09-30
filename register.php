<?php require_once __DIR__ . '/includes/config.php'; ?><?php require_once __DIR__ . '/includes/auth.php';
$reg_msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $login = trim($_POST['regLogin'] ?? '');
  $email = trim($_POST['regEmail'] ?? '');
  $password = trim($_POST['regPassword'] ?? '');
  $password2 = trim($_POST['regPassword2'] ?? '');
  if ($password !== $password2) {
    $reg_msg = 'Пароли не совпадают';
  } else {
    $res = create_user($login, $email, $password);
    if ($res['ok']) {
      login_user($login);
      header('Location: profile.php');
      exit;
    } else {
      $reg_msg = $res['msg'];
    }
  }
} ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CROW — Регистрация</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="register-page">

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
                    <h2 class="auth-title">Регистрация</h2>
                    <form class="auth-form" id="registerForm" method="POST" action="register.php" novalidate>
                        <div class="input-group">
                            <input type="text" id="regLogin" class="auth-input" placeholder="Логин" required name="regLogin">
                        </div>
                        <div class="input-group">
                            <input type="email" id="regEmail" class="auth-input" placeholder="Email" required name="regEmail">
                        </div>
                        <div class="input-group">
                            <input type="password" id="regPassword" class="auth-input" placeholder="Пароль" required name="regPassword">
                        </div>
                        <div class="input-group">
                            <input type="password" id="regPassword2" class="auth-input" placeholder="Повторите пароль" required name="regPassword2">
                        </div>
                        <button type="submit" class="submit-button">Зарегистрироваться</button>
                        <?php if (!empty($reg_msg)): ?><div class="form-message error" id="registerMessage" aria-live="polite"><?php echo htmlspecialchars($reg_msg); ?></div><?php else: ?><div class="form-message" id="registerMessage" aria-live="polite"></div><?php endif; ?>
                    </form>
                </div>
            </div>
            <div class="login-row">
                <a href="login.php" class="login-link">Уже есть аккаунт? Войти</a>
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
