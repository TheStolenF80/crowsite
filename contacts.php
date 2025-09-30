<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CROW — Контакты</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="contacts-page">

    <div class="container">
        <header class="header">
            <img class="logo" src="img/crow.png" alt="CROW Logo">
            
            <nav class="navigation">
                <button class="nav-button" data-target="#home" data-page="home">Главная</button>
                <button class="nav-button" data-target="#goods" data-page="goods">Товары</button>
                <button class="nav-button" data-target="#support" data-page="support">Поддержка</button>
                <button class="nav-button active" data-target="#contacts" data-page="contacts">Контакты</button>
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
            <h1 class="page-title">Наши контакты</h1>
            
            <div class="contact-card">
                <div class="contact-info">
                    <p class="description">
                        Мы всегда готовы подсказать и помочь вам с выбором!! Все ответы,
                        на ваши вопросы, вы сможете узнать по номеру телефона указанный
                        ниже, ждем вас!!♡
                    </p>
                    
                    <div class="contact-items">
                        <div class="contact-item">
                            <div class="icon-wrapper">
                                <div class="icon-blur address-blur"></div>
                                <img src="img/free-icon-pin-map-4230161-1.png" alt="Адрес" class="contact-icon">
                            </div>
                            <span class="contact-text">Университетский пр., 4-а, Москва</span>
                        </div>
                        
                        <div class="contact-item">
                            <div class="icon-wrapper">
                                <div class="icon-blur phone-blur"></div>
                                <img src="img/free-icon-phone-call-126509-1.png" alt="Телефон" class="contact-icon">
                            </div>
                            <span class="contact-text">+7900000000</span>
                        </div>
                        
                        <div class="contact-item">
                            <div class="icon-wrapper">
                                <div class="icon-blur email-blur"></div>
                                <img src="img/free-icon-speed-test-3573856-1.png" alt="Email" class="contact-icon">
                            </div>
                            <span class="contact-text">CROW@MAIL.RU</span>
                        </div>
                    </div>
                </div>
                
                <div class="map-section">
                    <img src="img/map.png" alt="Карта" class="map-image">
                    <button class="map-button" onclick="showOnMap()">Показать на карте</button>
                </div>
            </div>
        </main>

        <footer class="footer">
            <div class="social-icons">
                <img src="img/free-icon-telegram-2111710-1.png" alt="Telegram" class="social-icon" onclick="openSocialLink('telegram')">
                <img src="img/free-icon-instagram-1384015-1.png" alt="Instagram" class="social-icon" onclick="openSocialLink('instagram')">
                <img src="img/free-icon-youtube-4494467-1.png" alt="YouTube" class="social-icon" onclick="openSocialLink('youtube')">
                <img src="img/free-icon-facebook-4494464-1.png" alt="Facebook" class="social-icon" onclick="openSocialLink('facebook')">
            </div>
        </footer>
    </div>

    <script src="script.js"></script>

<script>
  window.IS_AUTH = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>
<script src="script.js"></script>
</body>
</html>
