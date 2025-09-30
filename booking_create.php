<?php
require_once __DIR__ . '/includes/auth.php';
require_login();
$user = current_user_row();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $resource = trim($_POST['resource'] ?? 'pc');
  $notes = $_POST['notes'] ?? null;

  $stmt = db()->prepare("INSERT INTO bookings (user_id, resource, notes) VALUES (?, ?, ?)");
  $stmt->execute([$user['id'], $resource, $notes]);

  header('Location: my_booking.php?s=created');
  exit;
}

header('Location: my_booking.php');
