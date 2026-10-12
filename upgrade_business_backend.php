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

function generateInviteCode($length = 8) {
    return substr(str_shuffle('ABCDEFGHJKLMNPQRSTUVWXYZ23456789'), 0, $length);
}
$invite_code = generateInviteCode();

$sql = "UPDATE users SET plan='business', paypal_subscription_id=?, role='owner', invite_code=? WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sss", $paypal_subscription_id, $invite_code, $username);

if ($stmt->execute()) {
    echo "redirect_dashboard|".$invite_code;
} else {
    echo "Fehler beim Upgrade.";
}
?>
