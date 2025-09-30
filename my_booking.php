<?php require_once __DIR__ . '/includes/config.php'; require_once __DIR__ . '/includes/auth.php'; require_login(); $u = current_user_row(); ?>
<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>CROW — Моя бронь</title>
  <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
</head>
<body class="login-page">
  <div class="main-container">
    <div class="background-text">CROW</div>
    <header class="header">
      <img class="logo" src="img/crow.png" alt="Crow Logo">
      <nav class="navigation">
        <button class="nav-button" data-page="home" onclick="window.location.href='index.php'">Главная</button>
        <button class="nav-button" data-page="goods" onclick="window.location.href='goods.php'">Товары</button>
        <button class="nav-button" data-page="support" onclick="window.location.href='support.php'">Поддержка</button>
        <button class="nav-button" data-page="contacts" onclick="window.location.href='contacts.php'">Контакты</button>
      <?php if (!empty($_SESSION['is_admin'])): ?>

          <button class="nav-button" onclick="window.location.href='admin.php'">&#1040;&#1076;&#1084;&#1080;&#1085;&#1082;&#1072;</button>

      <?php endif; ?>

      </nav>
      <div class="user-account" onclick="window.location.href='profile.php'">Личный кабинет (<?php echo htmlspecialchars($u['login']); ?>)</div>
    </header>

    <div class="auth-container">
      <div class="auth-card">
        <div class="glow-effect"></div>
        <div class="auth-content">
          <h2 class="auth-title">Моя бронь</h2>

          <?php
          $stmt = db()->prepare("SELECT * FROM bookings WHERE user_id = ? ORDER BY created_at DESC");
          $stmt->execute([$u['id']]);
          $items = $stmt->fetchAll();

            $statusMap = [
              'active'    => 'Активен',
              'cancelled' => 'Отменён',
              'completed' => 'Завершён',
            ];
          ?>

          <div style="margin-top:18px; color:#fff;">
            <?php if (!$items): ?>
              <p style="text-align:center;">Пока нет бронирований.</p>
            <?php else: ?>
              <table style="width:100%; border-collapse:collapse; font-size:16px;">
                <thead>
                  <tr style="text-align:left;">
                    <th style="padding:8px 6px;">ID</th>
                    <th style="padding:8px 6px;">Товар</th>
                    <th style="padding:8px 6px;">Статус</th>
                    <th style="padding:8px 6px;">Действия</th>
                  </tr>
                </thead>
                <tbody>
                <?php foreach ($items as $b): ?>
                  <tr>
                    <td style="padding:8px 6px;"><?php echo $b['id']; ?></td>
                    <td style="padding:8px 6px;"><?php echo htmlspecialchars($b['resource']); ?></td>
                    <td style="padding:8px 6px;">
                      <?php echo htmlspecialchars($statusMap[$b['status']] ?? $b['status']); ?>
                    </td>
                    <td style="padding:8px 6px;">
                      <?php if ($b['status'] === 'active'): ?>
                        <a href="booking_cancel.php?id=<?php echo $b['id']; ?>" style="color:#ffb1b1; text-decoration:none;">Отменить</a>
                      <?php else: ?>—<?php endif; ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
                </tbody>
              </table>
            <?php endif; ?>
          </div>

          <h3 class="auth-title" style="margin-top:24px;">Создать бронь</h3>
          <form class="auth-form" method="POST" action="booking_create.php" style="margin-top:12px;">
            <div class="input-group">
              <input class="auth-input" type="text" name="resource" placeholder="Товар (например, PC-01)" required>
            </div>
            <div class="input-group">
              <input class="auth-input" type="text" name="notes" placeholder="Комментарий (необязательно)">
            </div>
            <button type="submit" class="submit-button">Забронировать</button>
          </form>

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
