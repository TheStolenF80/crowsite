<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CROW — Еда</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="food-page">

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
            <section class="product-section drinks-section">
                <div class="section-content">
                    <h2 class="section-title">Напитки</h2>
                    
                    <div class="product-card">
                        <div class="product-list">
                            <div class="product-item horizontal">
                                <img src="img/redbull.png" alt="Redbull" class="product-image">
                                <span class="product-name">Redbull</span>
                                <span class="product-price">149₽</span>
                            </div>
                            <div class="product-item horizontal">
                                <img src="img/monster.png" alt="Monster" class="product-image">
                                <span class="product-name">Monster</span>
                                <span class="product-price">279₽</span>
                            </div>
                            <div class="product-item horizontal">
                                <img src="img/monsterultra.png" alt="Monster" class="product-image">
                                <span class="product-name">Monster</span>
                                <span class="product-price">289₽</span>
                            </div>
                            <div class="product-item horizontal">
                                <img src="img/burn.png" alt="Burn" class="product-image">
                                <span class="product-name">Burn</span>
                                <span class="product-price">175₽</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="blur-effect blue-blur"></div>
                <img class="decorative-element" src="img/blue.png" alt="Element">
            </section>

            <section class="product-section food-section">
                <div class="section-content">
                    <h2 class="section-title">Готовая еда</h2>
                    
                    <div class="product-card">
                        <div class="product-list vertical">
                            <div class="product-item vertical">
                                <img src="img/chebupizza.png" alt="Чебупицца" class="product-image large">
                                <span class="product-name">Чебупицца</span>
                                <span class="product-price">210₽</span>
                            </div>
                            <div class="product-item vertical">
                                <img src="img/rollton.png" alt="Роллтон" class="product-image large">
                                <span class="product-name">Роллтон</span>
                                <span class="product-price">80₽</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="blur-effect green-blur"></div>
            </section>
            <section class="product-section snacks-section">
                <div class="section-content">
                    <h2 class="section-title">Снэки</h2>
                    
                    <div class="product-card">
                        <div class="product-list vertical">
                            <div class="product-item vertical">
                                <img src="img/lays.png" alt="Lays" class="product-image large">
                                <span class="product-name">Lays</span>
                                <span class="product-price">199₽</span>
                            </div>
                            <div class="product-item vertical">
                                <img src="img/popcorn.png" alt="Попкорн" class="product-image large">
                                <span class="product-name">Попкорн</span>
                                <span class="product-price">250₽</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="blur-effect orange-blur"></div>
                <img class="decorative-element right" src="img/purple.png" alt="Element">
            </section>
        </main>
    </div>

    <script src="script.js"></script>

<script>
  window.IS_AUTH = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>
<script src="script.js"></script>
</body>
</html>
