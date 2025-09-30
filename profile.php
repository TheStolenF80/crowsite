<?php require_once __DIR__ . '/includes/config.php'; require_once __DIR__ . '/includes/auth.php'; require_login(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CROW — Личный кабинет</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">
  <div class="main-container">
    <div class="background-text">CROW</div>
    <header class="header">
      <img class="logo" src="img/crow.png" alt="Crow Logo">
      <nav class="navigation">
        <button class="nav-button" data-page="home">Главная</button>
        <button class="nav-button" data-page="goods">Товары</button>
        <button class="nav-button" data-page="support">Поддержка</button>
        <button class="nav-button" data-page="contacts">Контакты</button>
      <?php if (!empty($_SESSION['is_admin'])): ?>

          <button class="nav-button" onclick="window.location.href='admin.php'">&#1040;&#1076;&#1084;&#1080;&#1085;&#1082;&#1072;</button>

      <?php endif; ?>

      </nav>
      <div class="user-account" onclick="window.location.href='logout.php'">Выйти</div>
    </header>

    <div class="auth-container">
      <div class="auth-card">
        <div class="glow-effect"></div>
        <div class="auth-content">
          <h2 class="auth-title">Добро пожаловать, <?php echo htmlspecialchars($_SESSION['user']); ?>!</h2>
          <div class="input-group" style="margin-top:18px;">
            <button class="submit-button" onclick="window.location.href='my_booking.php'">Моя бронь</button>
          </div>
        </div>
      </div>
    </div>

    <img src="img/light.png" alt="Background" class="bg-image bg-image-1">
    <img src="img/purple5.png" alt="Background" class="bg-image bg-image-2">
    <img src="img/purple4.png" alt="Background" class="bg-image bg-image-3">
  </div>
  <script>window.IS_AUTH = true;</script>
  <script src="script.js"></script>
</body>
</html>
