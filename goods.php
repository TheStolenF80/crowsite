<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CROW — Товары</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="goods-page">

    <div class="container">
        <header class="header">
            <img class="logo" src="img/crow.png" alt="Crow Logo">
            <nav class="navigation">
                <button class="nav-button" data-target="#home" data-page="home">Главная</button>
                <button class="nav-button active" data-target="#goods" data-page="goods">Товары</button>
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
            <div class="product-category" data-category="consoles">
                <div class="category-card">
                    <div class="card-background"></div>
                    <h2 class="category-title">Приставки</h2>
                </div>
                
                <div class="blur-effect blur-consoles"></div>
                
                <img class="product-image ps5-image" alt="PS5" src="img/ps5-1.png" />
                <img class="product-image element-1" alt="Element" src="img/blue.png" />
            </div>
            
            <div class="product-category food-category" data-category="food">
                <div class="category-card food-card">
                    <div class="card-background"></div>
                    <h2 class="category-title food-title">Еда</h2>
                </div>
                
                <div class="blur-effect blur-food"></div>
                
                <img class="product-image food-element-1" alt="Element" src="img/minecraft-food.png" />
                <img class="product-image food-element-2" alt="Element" src="img/coffee.png" />
                <img class="product-image tofu-burger" alt="Tofu burger" src="img/burger.png" />
                <img class="product-image food-element-3" alt="Element" src="img/green.png" />
            </div>

            <div class="product-category pc-category" data-category="pc">
                <div class="category-card pc-card">
                    <div class="card-background"></div>
                    <h2 class="category-title pc-title">PC</h2>
                </div>
                
                <div class="blur-effect blur-pc"></div>
                
                <img class="product-image pc-element-1" alt="Element" src="img/purple.png" />
                <img class="product-image pc-image" alt="PC" src="img/pc.png" />
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
