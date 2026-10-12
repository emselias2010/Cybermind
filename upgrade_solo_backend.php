<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo "Nicht eingeloggt.";
    exit;
}
include 'db.php';

$username = $_SESSION['username'];
$paypal_subscription_id = $_POST['paypal_subscription_id'] ?? '';

if (!$paypal_subscription_id) {
    echo "PayPal Subscription ID fehlt.";
    exit;
}

$sql = "UPDATE users SET plan='solo', paypal_subscription_id=? WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $paypal_subscription_id, $username);

if ($stmt->execute()) {
    echo "success";
} else {
    echo "Fehler beim Upgrade.";
}
?>
