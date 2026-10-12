<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: business_register.php");
    exit;
}
include 'db.php';

$username = $_SESSION['username'];

$sql = "SELECT plan, invite_code FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

$is_business = ($user && $user['plan'] === 'business');
$invite_code = $user['invite_code'] ?? '';

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport"
      content="width=device-width, initial-scale=1.0, minimum-scale=0.9, maximum-scale=0.9">
    <meta charset="UTF-8">
    <title>Business-Abo bezahlen – CyberMind</title>
    <link rel="icon" type="image/png" href="Icon.png">
    <style>
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #0b1630ff, #1e293b);
            color: white;
        }
        .center-box {
            max-width: 400px;
            margin: 60px auto;
            background: #334155;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px;
            text-align: center;
            color: #ffffffff;
        }
        .logo {
            width: 120px;
            margin-bottom: 24px;
        }
        h2 {
            color: #ffffffff;
            margin-bottom: 24px;
        }
        .success {
            color: #ffffffff;
            margin-bottom: 20px;
        }
        .error {
            color: #e74c3c;
            margin-bottom: 16px;
        }
        footer {
            text-align: center;
            margin-top: 40px;
            color: #94a3b8;
            font-size: 15px;
        }
        a {
            color: #0070f3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="center-box">
        <img class="logo" src="Logo_no_background.png" alt="CyberMind Logo">
        <h2>Business-Abo bezahlen</h2>
        <?php if ($is_business): ?>
            <div class="success">
                Dein Business-Abo ist aktiv.<br>
                Invite-Code: <b><?php echo htmlspecialchars($invite_code); ?></b><br>
                Du kannst bis zu 100 Accounts mit diesem Code einladen.
            </div>
        <?php else: ?>
            <p>Um das Business-Abo zu aktivieren, zahle bitte mit PayPal:</p>
            <div id="paypal-button-container-business"></div>
            <div id="errorMsg" class="error"></div>
        <?php endif; ?>
    </div>
    <footer>
        © 2025 CyberMind – Alle Rechte vorbehalten
    </footer>
    <?php if (!$is_business): ?>
    <script src="https://www.paypal.com/sdk/js?client-id=YOUR_PAYPAL_CLIENT_ID&vault=true&intent=subscription" data-sdk-integration-source="button-factory"></script>
    <script>
      paypal.Buttons({
          style: {
              shape: 'rect',
              color: 'blue',
              layout: 'vertical',
              label: 'subscribe'
          },
          createSubscription: function(data, actions) {
            return actions.subscription.create({
              plan_id: 'P-91581222FR2583827NCXRMEI'
            });
          },
          onApprove: function(data, actions) {
            fetch('upgrade_business_backend.php', {
                method: 'POST',
                headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                body: 'paypal_subscription_id=' + encodeURIComponent(data.subscriptionID)
            })
            .then(response => response.text())
            .then(result => {
                if (result.startsWith("redirect_dashboard")) {
                    window.location.href = "dashboard.php";
                } else {
                    document.getElementById('errorMsg').innerText = result;
                }
            });
          }
      }).render('#paypal-button-container-business');
    </script>
    <?php endif; ?>
</body>
</html>
