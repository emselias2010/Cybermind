<?php
session_start();
if (isset($_SESSION['username'])) {
    header("Location: payment_business.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport"
      content="width=device-width, initial-scale=1.0, minimum-scale=0.9, maximum-scale=0.9">
    <meta charset="UTF-8">
    <title>Solo-Abo Registrierung & Zahlung – CyberMind</title>
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
        input[type="text"], input[type="password"], input[type="email"], input[type="tel"] {
            width: 90%;
            padding: 10px;
            margin: 10px 0 20px 0;
            border: 1px solid #dbe2e8;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 12px 32px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #2563eb;
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
        <h2>Solo-Abo Registrierung & Zahlung</h2>
        <form id="regForm">
            <input type="text" name="username" id="username" placeholder="Benutzername" required><br>
            <input type="email" name="email" id="email" placeholder="E-Mail" required><br>
            <input type="tel" name="phone" id="phone" placeholder="Handynummer" required><br>
            <input type="password" name="password" id="password" placeholder="Passwort" required><br>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Passwort bestätigen" required><br>
        </form>
        <div id="paypal-button-container-solo"></div>
        <div id="errorMsg" class="error"></div>
        <p>Schon ein Konto? <a href="login.php">Login</a></p>
    </div>
    <footer>
        © 2025 CyberMind – Alle Rechte vorbehalten
    </footer>
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
            let username = document.getElementById('username').value.trim();
            let email = document.getElementById('email').value.trim();
            let phone = document.getElementById('phone').value.trim();
            let password = document.getElementById('password').value;
            let confirm_password = document.getElementById('confirm_password').value;
            let errorMsg = "";

            if (!username || !email || !phone || !password || !confirm_password) {
                errorMsg = "Bitte alle Felder ausfüllen!";
            } else if (password !== confirm_password) {
                errorMsg = "Die Passwörter stimmen nicht überein!";
            }

            if (errorMsg) {
                document.getElementById('errorMsg').innerText = errorMsg;
                return false;
            } else {
                document.getElementById('errorMsg').innerText = "";
                return actions.subscription.create({
                  plan_id: 'P-71F90367X56738121NCYXZJI'
                });
            }
          },
          onApprove: function(data, actions) {
            let formData = new FormData(document.getElementById('regForm'));
            formData.append('paypal_subscription_id', data.subscriptionID);

            fetch('solo_register_backend.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(result => {
                if (result === "success") {
                    window.location.href = "login.php";
                } else {
                    document.getElementById('errorMsg').innerText = result;
                }
            });
          }
      }).render('#paypal-button-container-solo');
    </script>
</body>
</html>
