<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CROW — Приставки</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="console-page">

    <div class="container">
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

        <main class="main-content">
            <div class="product-container ps5-container">
                <div class="product-card">
                    <h2 class="product-title">Playstation 5</h2>
                </div>
                <div class="glow-effect ps5-glow"></div>
                <img class="product-image ps5-image" alt="PlayStation 5" src="img/ps5-1.png" />
                <img class="decorative-element ps5-decorative" alt="Element" src="img/blue.png" />
            </div>

            <div class="product-container xbox-container">
                <div class="product-card">
                    <h2 class="product-title xbox-title">Xbox X</h2>
                </div>
                <div class="glow-effect xbox-glow"></div>
                <img class="product-image xbox-image" alt="Xbox X" src="img/xbox.png" />
                <img class="decorative-element xbox-decorative" alt="Element" src="img/green.png" />
            </div>

            <div class="product-container nintendo-container">
                <div class="product-card">
                    <h2 class="product-title nintendo-title">Nintendo</h2>
                </div>
                <div class="glow-effect nintendo-glow"></div>
                <div class="glow-effect nintendo-glow-red"></div>
                <img class="product-image nintendo-image" alt="Nintendo" src="img/nintendo-switch.png" />
                <img class="decorative-element nintendo-decorative" alt="Element" src="img/purple.png" />
            </div>
        </main>
    </div>

    <script src="script.js"></script>

<script>
  window.IS_AUTH = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>
<script src="script.js"></script>
</body>
</html>
