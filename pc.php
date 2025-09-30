<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CROW — Компьютеры</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="pc-page">
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
            <div class="product-card-container">
                <div class="product-section">
                    <h2 class="product-title rtx-3060-title">RTX 3060</h2>
                    <div class="product-card">
                        <div class="glow-effect glow-blue"></div>
                        <img class="product-image rtx-3060-image" src="img/rtx3060.png" alt="RTX 3060">
                    </div>
                </div>
            </div>

            <div class="product-card-container rtx-4060-container">
                <div class="product-section">
                    <h2 class="product-title rtx-4060-title">RTX 4060</h2>
                    <div class="product-card">
                        <div class="glow-effect glow-green"></div>
                        <img class="product-image rtx-4060-image" src="img/rtx4060.png" alt="RTX 4060">
                    </div>
                </div>
            </div>

            <div class="product-card-container rtx-5090-container">
                <div class="product-section">
                    <h2 class="product-title rtx-5090-title">RTX 5090</h2>
                    <div class="product-card">
                        <div class="glow-effect glow-orange"></div>
                        <img class="product-image rtx-5090-image" src="img/rtx5090.png" alt="RTX 5090">
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        window.IS_AUTH = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
    </script>
    <script src="script.js"></script>
</body>
</html>