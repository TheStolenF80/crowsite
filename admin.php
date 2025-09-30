<?php
require_once __DIR__ . '/includes/auth.php';
require_admin();

$pdo = db();

$statuses = [
    'active'    => 'Активно',
    'cancelled' => 'Отменено',
    'completed' => 'Завершено',
];

$flash = $_SESSION['admin_flash'] ?? ['success' => [], 'error' => []];
unset($_SESSION['admin_flash']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entity   = $_POST['entity'] ?? '';
    $action   = $_POST['action'] ?? '';
    $messages = ['success' => [], 'error' => []];

    if ($entity === 'user') {
        if ($action === 'create') {
            $login    = trim($_POST['login'] ?? '');
            $email    = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');
            $isAdmin  = !empty($_POST['is_admin']) ? 1 : 0;

            if ($login === '' || mb_strlen($login) < 3) {
                $messages['error'][] = 'Логин должен содержать минимум 3 символа.';
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $messages['error'][] = 'Введите корректный e-mail.';
            }
            if (mb_strlen($password) < 6) {
                $messages['error'][] = 'Пароль должен содержать минимум 6 символов.';
            }

            if (!$messages['error']) {
                try {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $pdo->prepare('INSERT INTO users (login, email, pass, is_admin) VALUES (?, ?, ?, ?)');
                    $stmt->execute([$login, $email, $hash, $isAdmin]);
                    $messages['success'][] = 'Пользователь «' . $login . '» создан.';
                } catch (Throwable $e) {
                    $messages['error'][] = 'Не удалось создать пользователя. Проверьте уникальность логина и e-mail.';
                }
            }
        } elseif ($action === 'update') {
            $id         = (int)($_POST['id'] ?? 0);
            $login      = trim($_POST['login'] ?? '');
            $email      = trim($_POST['email'] ?? '');
            $isAdmin    = !empty($_POST['is_admin']) ? 1 : 0;
            $newPassRaw = trim($_POST['new_password'] ?? '');

            $current = $id > 0 ? find_user_by_id($id) : null;
            if (!$current) {
                $messages['error'][] = 'Пользователь не найден.';
            }

            if (!$messages['error']) {
                if ($login === '' || mb_strlen($login) < 3) {
                    $messages['error'][] = 'Логин должен содержать минимум 3 символа.';
                }
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $messages['error'][] = 'Введите корректный e-mail.';
                }
            }

            if (!$messages['error']) {
                try {
                    $dupLogin = $pdo->prepare('SELECT id FROM users WHERE LOWER(login) = LOWER(?) AND id <> ? LIMIT 1');
                    $dupLogin->execute([$login, $id]);
                    if ($dupLogin->fetch()) {
                        $messages['error'][] = 'Такой логин уже используется.';
                    }

                    $dupEmail = $pdo->prepare('SELECT id FROM users WHERE LOWER(email) = LOWER(?) AND id <> ? LIMIT 1');
                    $dupEmail->execute([$email, $id]);
                    if ($dupEmail->fetch()) {
                        $messages['error'][] = 'Такой e-mail уже используется.';
                    }

                    $fields = ['login' => $login, 'email' => $email, 'is_admin' => $isAdmin];
                    if ($newPassRaw !== '') {
                        if (mb_strlen($newPassRaw) < 6) {
                            $messages['error'][] = 'Новый пароль должен содержать минимум 6 символов.';
                        } else {
                            $fields['pass'] = password_hash($newPassRaw, PASSWORD_DEFAULT);
                        }
                    }

                    if (!$messages['error']) {
                        $sqlParts = [];
                        $params   = [];
                        foreach ($fields as $column => $value) {
                            $sqlParts[] = $column . ' = ?';
                            $params[]   = $value;
                        }
                        $params[] = $id;

                        $sql = 'UPDATE users SET ' . implode(', ', $sqlParts) . ' WHERE id = ?';
                        $stmt = $pdo->prepare($sql);
                        $stmt->execute($params);

                        if (current_user_id() === $id) {
                            $updated = find_user_by_id($id);
                            if ($updated) {
                                login_user($updated);
                            }
                        }

                        $messages['success'][] = 'Данные пользователя обновлены.';
                    }
                } catch (Throwable $e) {
                    $messages['error'][] = 'Не удалось обновить пользователя.';
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                $messages['error'][] = 'Некорректный идентификатор пользователя.';
            } elseif (current_user_id() === $id) {
                $messages['error'][] = 'Нельзя удалить собственный аккаунт.';
            } else {
                try {
                    $stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
                    $stmt->execute([$id]);
                    if ($stmt->rowCount()) {
                        $messages['success'][] = 'Пользователь удалён.';
                    } else {
                        $messages['error'][] = 'Пользователь не найден.';
                    }
                } catch (Throwable $e) {
                    $messages['error'][] = 'Не удалось удалить пользователя.';
                }
            }
        }
    } elseif ($entity === 'booking') {
        if ($action === 'create') {
            $userId   = (int)($_POST['user_id'] ?? 0);
            $resource = trim($_POST['resource'] ?? '');
            $status   = $_POST['status'] ?? 'active';
            $notes    = trim($_POST['notes'] ?? '');

            if ($userId <= 0 || !find_user_by_id($userId)) {
                $messages['error'][] = 'Выберите существующего пользователя.';
            }
            if ($resource === '') {
                $messages['error'][] = 'Укажите ресурс.';
            }
            if (!isset($statuses[$status])) {
                $messages['error'][] = 'Некорректный статус бронирования.';
            }

            if (!$messages['error']) {
                try {
                    $stmt = $pdo->prepare('INSERT INTO bookings (user_id, resource, status, notes) VALUES (?, ?, ?, ?)');
                    $stmt->execute([$userId, $resource, $status, $notes !== '' ? $notes : null]);
                    $messages['success'][] = 'Бронирование создано.';
                } catch (Throwable $e) {
                    $messages['error'][] = 'Не удалось создать бронирование.';
                }
            }
        } elseif ($action === 'update') {
            $id       = (int)($_POST['id'] ?? 0);
            $userId   = (int)($_POST['user_id'] ?? 0);
            $resource = trim($_POST['resource'] ?? '');
            $status   = $_POST['status'] ?? 'active';
            $notes    = trim($_POST['notes'] ?? '');

            if ($id <= 0) {
                $messages['error'][] = 'Некорректный идентификатор бронирования.';
            }
            if ($userId <= 0 || !find_user_by_id($userId)) {
                $messages['error'][] = 'Выберите существующего пользователя.';
            }
            if ($resource === '') {
                $messages['error'][] = 'Укажите ресурс.';
            }
            if (!isset($statuses[$status])) {
                $messages['error'][] = 'Некорректный статус бронирования.';
            }

            if (!$messages['error']) {
                try {
                    $stmt = $pdo->prepare('UPDATE bookings SET user_id = ?, resource = ?, status = ?, notes = ? WHERE id = ?');
                    $stmt->execute([$userId, $resource, $status, $notes !== '' ? $notes : null, $id]);
                    $messages['success'][] = 'Бронирование обновлено.';
                } catch (Throwable $e) {
                    $messages['error'][] = 'Не удалось обновить бронирование.';
                }
            }
        } elseif ($action === 'delete') {
            $id = (int)($_POST['id'] ?? 0);
            if ($id <= 0) {
                $messages['error'][] = 'Некорректный идентификатор бронирования.';
            } else {
                try {
                    $stmt = $pdo->prepare('DELETE FROM bookings WHERE id = ?');
                    $stmt->execute([$id]);
                    if ($stmt->rowCount()) {
                        $messages['success'][] = 'Бронирование удалено.';
                    } else {
                        $messages['error'][] = 'Бронирование не найдено.';
                    }
                } catch (Throwable $e) {
                    $messages['error'][] = 'Не удалось удалить бронирование.';
                }
            }
        }
    }

    $_SESSION['admin_flash'] = $messages;
    header('Location: admin.php');
    exit;
}

try {
    $usersStmt = $pdo->query('SELECT id, login, email, is_admin, created_at FROM users ORDER BY id ASC');
    $users = $usersStmt ? $usersStmt->fetchAll() : [];
} catch (Throwable $e) {
    $users = [];
}

try {
    $bookingsStmt = $pdo->query('SELECT b.*, u.login AS user_login FROM bookings b LEFT JOIN users u ON b.user_id = u.id ORDER BY b.created_at DESC');
    $bookings = $bookingsStmt ? $bookingsStmt->fetchAll() : [];
} catch (Throwable $e) {
    $bookings = [];
}
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CROW — Админка</title>
    <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue:wght@400&family=Orbitron:wght@400&family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="admin-page">
    <div class="main-container">
        <div class="background-text">CROW</div>
        <header class="header">
            <img class="logo" src="img/crow.png" alt="Логотип Crow">
            <nav class="navigation">
                <button class="nav-button" onclick="window.location.href='index.php'">Главная</button>
                <button class="nav-button" onclick="window.location.href='goods.php'">Залы</button>
                <button class="nav-button" onclick="window.location.href='support.php'">Поддержка</button>
                <button class="nav-button" onclick="window.location.href='contacts.php'">Контакты</button>
                <button class="nav-button active" onclick="window.location.href='admin.php'">Админка</button>
            </nav>
            <div class="user-account" onclick="window.location.href='profile.php'">
                Кабинет (<?php echo htmlspecialchars(current_user_login()); ?>)
            </div>
        </header>

        <div class="admin-content">
            <?php if (!empty($flash['success']) || !empty($flash['error'])): ?>
                <div class="admin-flash">
                    <?php foreach ($flash['success'] as $msg): ?>
                        <div class="admin-alert success"><?php echo htmlspecialchars($msg); ?></div>
                    <?php endforeach; ?>
                    <?php foreach ($flash['error'] as $msg): ?>
                        <div class="admin-alert error"><?php echo htmlspecialchars($msg); ?></div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>

            <section class="admin-card">
                <div class="card-header">
                    <h2>Управление пользователями</h2>
                    <p>Создавайте, редактируйте и удаляйте аккаунты посетителей и сотрудников.</p>
                </div>
                <form class="admin-form" method="post">
                    <input type="hidden" name="entity" value="user">
                    <input type="hidden" name="action" value="create">
                    <div class="form-grid">
                        <label>
                            <span>Логин</span>
                            <input type="text" name="login" required placeholder="Например, crow_admin">
                        </label>
                        <label>
                            <span>E-mail</span>
                            <input type="email" name="email" required placeholder="name@example.com">
                        </label>
                        <label>
                            <span>Пароль</span>
                            <input type="password" name="password" required placeholder="Минимум 6 символов">
                        </label>
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_admin" value="1">
                            <span>Администратор</span>
                        </label>
                        <button type="submit" class="admin-button">Добавить пользователя</button>
                    </div>
                </form>

                <div class="table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Логин</th>
                                <th>E-mail</th>
                                <th>Роль</th>
                                <th>Создан</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$users): ?>
                                <tr>
                                    <td colspan="6" class="empty-cell">Пользователи ещё не созданы.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($users as $row): ?>
                                    <?php $formId = 'user-update-' . $row['id']; ?>
                                    <tr>
                                        <td><?php echo (int)$row['id']; ?></td>
                                        <td>
                                            <input form="<?php echo $formId; ?>" type="text" name="login" value="<?php echo htmlspecialchars($row['login']); ?>" required>
                                        </td>
                                        <td>
                                            <input form="<?php echo $formId; ?>" type="email" name="email" value="<?php echo htmlspecialchars($row['email']); ?>" required>
                                        </td>
                                        <td>
                                            <select form="<?php echo $formId; ?>" name="is_admin">
                                                <option value="0" <?php echo empty($row['is_admin']) ? 'selected' : ''; ?>>Пользователь</option>
                                                <option value="1" <?php echo !empty($row['is_admin']) ? 'selected' : ''; ?>>Администратор</option>
                                            </select>
                                        </td>
                                        <td><?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($row['created_at']))); ?></td>
                                        <td class="actions-cell">
                                            <div class="action-row">
                                                <input form="<?php echo $formId; ?>" type="password" name="new_password" placeholder="Новый пароль">
                                                <button form="<?php echo $formId; ?>" type="submit" class="admin-button small">Сохранить</button>
                                            </div>
                                            <?php if (current_user_id() !== (int)$row['id']): ?>
                                                <form method="post" class="inline-form" onsubmit="return confirm('Удалить пользователя?');">
                                                    <input type="hidden" name="entity" value="user">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                                                    <button type="submit" class="admin-button danger small">Удалить</button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <form id="<?php echo $formId; ?>" method="post" class="inline-form">
                                        <input type="hidden" name="entity" value="user">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id" value="<?php echo (int)$row['id']; ?>">
                                    </form>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <section class="admin-card">
                <div class="card-header">
                    <h2>Управление бронированиями</h2>
                    <p>Следите за расписанием и обновляйте заявки гостей.</p>
                </div>
                <form class="admin-form" method="post">
                    <input type="hidden" name="entity" value="booking">
                    <input type="hidden" name="action" value="create">
                    <div class="form-grid">
                        <label>
                            <span>Пользователь</span>
                            <select name="user_id" required>
                                <option value="">Выберите пользователя</option>
                                <?php foreach ($users as $row): ?>
                                    <option value="<?php echo (int)$row['id']; ?>"><?php echo htmlspecialchars($row['login']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label>
                            <span>Ресурс</span>
                            <input type="text" name="resource" required placeholder="Например, PC-01">
                        </label>
                        <label>
                            <span>Статус</span>
                            <select name="status">
                                <?php foreach ($statuses as $key => $label): ?>
                                    <option value="<?php echo $key; ?>"><?php echo $label; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </label>
                        <label class="full-width">
                            <span>Заметки</span>
                            <input type="text" name="notes" placeholder="Комментарий для администраторов">
                        </label>
                        <button type="submit" class="admin-button">Добавить бронирование</button>
                    </div>
                </form>

                <div class="table-wrapper">
                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Пользователь</th>
                                <th>Ресурс</th>
                                <th>Статус</th>
                                <th>Заметки</th>
                                <th>Создано</th>
                                <th>Действия</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!$bookings): ?>
                                <tr>
                                    <td colspan="7" class="empty-cell">Записей пока нет.</td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($bookings as $booking): ?>
                                    <?php $formId = 'booking-update-' . $booking['id']; ?>
                                    <tr>
                                        <td><?php echo (int)$booking['id']; ?></td>
                                        <td>
                                            <select form="<?php echo $formId; ?>" name="user_id">
                                                <?php foreach ($users as $row): ?>
                                                    <option value="<?php echo (int)$row['id']; ?>" <?php echo (int)$row['id'] === (int)$booking['user_id'] ? 'selected' : ''; ?>><?php echo htmlspecialchars($row['login']); ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input form="<?php echo $formId; ?>" type="text" name="resource" value="<?php echo htmlspecialchars($booking['resource']); ?>" required>
                                        </td>
                                        <td>
                                            <select form="<?php echo $formId; ?>" name="status">
                                                <?php foreach ($statuses as $key => $label): ?>
                                                    <option value="<?php echo $key; ?>" <?php echo $booking['status'] === $key ? 'selected' : ''; ?>><?php echo $label; ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </td>
                                        <td>
                                            <input form="<?php echo $formId; ?>" type="text" name="notes" value="<?php echo htmlspecialchars($booking['notes'] ?? ''); ?>">
                                        </td>
                                        <td><?php echo htmlspecialchars(date('d.m.Y H:i', strtotime($booking['created_at']))); ?></td>
                                        <td class="actions-cell">
                                            <div class="action-row">
                                                <button form="<?php echo $formId; ?>" type="submit" class="admin-button small">Сохранить</button>
                                            </div>
                                            <form method="post" class="inline-form" onsubmit="return confirm('Удалить бронирование?');">
                                                <input type="hidden" name="entity" value="booking">
                                                <input type="hidden" name="action" value="delete">
                                                <input type="hidden" name="id" value="<?php echo (int)$booking['id']; ?>">
                                                <button type="submit" class="admin-button danger small">Удалить</button>
                                            </form>
                                        </td>
                                    </tr>
                                    <form id="<?php echo $formId; ?>" method="post" class="inline-form">
                                        <input type="hidden" name="entity" value="booking">
                                        <input type="hidden" name="action" value="update">
                                        <input type="hidden" name="id" value="<?php echo (int)$booking['id']; ?>">
                                    </form>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>

        <img src="img/light.png" alt="Фон" class="bg-image bg-image-1">
        <img src="img/purple5.png" alt="Фон" class="bg-image bg-image-2">
        <img src="img/purple4.png" alt="Фон" class="bg-image bg-image-3">
    </div>
    <script src="script.js"></script>
</body>
</html>