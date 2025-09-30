<?php require_once __DIR__ . "/includes/config.php"; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>CROW — Поддержка</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css" />
</head>
<body class="support-page">

    <div class="container">
        <header class="header">
            <img class="logo" src="img/crow.png" alt="Crow Logo">
            
            <nav class="navigation">
                <button class="nav-button" data-target="#home" data-page="home">Главная</button>
                <button class="nav-button" data-target="#goods" data-page="goods">Товары</button>
                <button class="nav-button active" data-target="#support" data-page="support">Поддержка</button>
                <button class="nav-button" data-target="#contacts" data-page="contacts">Контакты</button>
            <?php if (!empty($_SESSION['is_admin'])): ?>

                <button class="nav-button" onclick="window.location.href='admin.php'">&#1040;&#1076;&#1084;&#1080;&#1085;&#1082;&#1072;</button>

            <?php endif; ?>

            </nav>

            <?php if (isset($_SESSION["user"])): ?>
<div class="user-account" onclick="window.location.href='profile.php'">
  Личный кабинет (<?php echo htmlspecialchars($_SESSION["user"]); ?>)
</div>
<?php else: ?>
<div class="user-account" onclick="window.location.href='login.php'">
  Личный кабинет
</div>
<?php endif; ?>
        </header>

        <main class="main-content">
            <div class="content-card">
                <div class="support-message">
                    Мы всегда готовы подсказать и помочь вам!<br>
                    Свяжитесь с нами<br>
                    <a href="mailto:CROW@mail.ru" class="email-link">CROW@mail.ru</a>
                </div>
            </div>
        </main>
    </div>

    <script src="script.js"></script>

<script>
  window.IS_AUTH = <?php echo isset($_SESSION["user"]) ? "true" : "false"; ?>;
</script>
<script src="script.js"></script>
</body>
</html>
