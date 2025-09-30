<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
$user = current_user_row();
$id = intval($_GET['id'] ?? 0);
if ($id > 0) {
  $check = db()->prepare("SELECT id FROM bookings WHERE id = ? AND user_id = ?");
  $check->execute([$id, $user['id']]);
  if ($check->fetch()) {
    $stmt = db()->prepare("UPDATE bookings SET status='cancelled' WHERE id = ?");
    $stmt->execute([$id]);
  }
}
header('Location: my_booking.php?s=cancelled');
