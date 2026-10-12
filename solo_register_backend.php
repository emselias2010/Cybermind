<?php
include 'db.php';

$username = $_POST['username'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';
$paypal_subscription_id = $_POST['paypal_subscription_id'] ?? '';

if (!$username || !$email || !$phone || !$password || !$confirm_password || !$paypal_subscription_id) {
    echo "Bitte alle Felder ausfüllen!";
    exit;
}
if ($password !== $confirm_password) {
    echo "Die Passwörter stimmen nicht überein!";
    exit;
}

$sql = "SELECT id FROM users WHERE username=? OR email=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $username, $email);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "Benutzername oder E-Mail bereits vergeben!";
    exit;
}

$invite_code = null;
$role = 'owner';
$plan = 'solo';
$password_hash = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, email, phone, password, plan, invite_code, role, created_at, paypal_subscription_id) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $username, $email, $phone, $password_hash, $plan, $invite_code, $role, $paypal_subscription_id);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Fehler: " . $stmt->error;
}
?>
