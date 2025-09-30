<?php require_once __DIR__ . '/includes/config.php'; ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CROW — Компьютерный клуб</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="">

  <div class="container" id="home">
    <div class="speed-text speed-left">speed</div>
    <div class="speed-text speed-right">speed</div>

    <div class="main-title">CROW</div>
    <div class="gradient-overlay"></div>

    <div class="main-content">
      <div class="speech-bubble bubble-1">
        <div class="bubble-content">
          Давно мечтал посетить лучший копьютерный клуб в Москве?
        </div>
      </div>

      <div class="speech-bubble bubble-2">
        <div class="bubble-content">
          А также побывать в самом комфортном месте среди молодежи?
        </div>
      </div>

      <img class="main-image" src="img/avatars.png" alt="Main Image">
    </div>

    <header class="header">
      <img class="logo" src="img/crow.png" alt="Crow Logo">
      <nav class="navigation">
        <button class="nav-button active" data-target="#home" data-page="home">Главная</button>
        <button class="nav-button" data-target="#goods" data-page="goods">Товары</button>
        <button class="nav-button" data-target="#why-us" data-page="support">Поддержка</button>
        <button class="nav-button" data-target="#why-us" data-page="contacts">Контакты</button>
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

    <div class="bottom-gradient"></div>

    <button class="scroll-down-btn" onclick="scrollDown()"
      aria-label="Прокрутить вниз к блоку 'Почему выбирают нас'">
      <svg width="91" height="91" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
        <polyline points="6,9 12,15 18,9"></polyline>
      </svg>
    </button>
  </div>

  <section class="main-section" id="why-us">

    <header class="main-header">
      <h1>Почему выбирают нас?</h1>
    </header>

    <div class="features-container">
      <article class="feature-card" data-feature="performance">
        <div class="card-content">
          <div class="blur-effect blur-purple"></div>
          <div class="icon-container">
            <img src="img/free-icon-speed-test-3573856-1.png" alt="Высокая производительность" class="feature-icon">
          </div>
          <h3 class="feature-title">Высокая производительность</h3>
          <p class="feature-description">У нас вы сможете поиграть в любимые игры с максимальным комфортом, благодаря производительной технике</p>
        </div>
      </article>

      <article class="feature-card" data-feature="support">
        <div class="card-content">
          <div class="blur-effect blur-pink"></div>
          <div class="icon-container">
            <img src="img/free-icon-speed-test-3573856-1-1.png" alt="Поддержка" class="feature-icon">
          </div>
          <h3 class="feature-title">Поддержка</h3>
          <p class="feature-description">Наши специалисты всегда готовы оказать помощь, а также подсказать в случаях недопонимания</p>
        </div>
      </article>

      <article class="feature-card" data-feature="economy">
        <div class="card-content">
          <div class="blur-effect blur-red"></div>
          <div class="icon-container">
            <img src="img/free-icon-speed-test-3573856-1-2.png" alt="Экономия" class="feature-icon">
          </div>
          <h3 class="feature-title">Экономия</h3>
          <p class="feature-description">Благодаря низким ценам вы сможете поиграть в любимые игры по самой доступной цене</p>
        </div>
      </article>
    </div>
  </section>

  <script src="script.js"></script>

<script>
  window.IS_AUTH = <?php echo isset($_SESSION['user']) ? 'true' : 'false'; ?>;
</script>
<script src="script.js"></script>
</body>
</html>
