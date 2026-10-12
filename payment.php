<?php
session_start();
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport"
      content="width=device-width, initial-scale=1.0, minimum-scale=0.9, maximum-scale=0.9">
    <meta charset="UTF-8">
    <title>Zahlung erforderlich – CyberMind</title>
    <link rel="icon" type="image/png" href="Icon.png">
    <style>
        body {
            background: linear-gradient(135deg, #0b1630ff, #1e293b);
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #ffffff;
        }
        .center-box {
            max-width: 400px;
            margin: 60px auto;
            background: #334155;
            border-radius: 12px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.08);
            padding: 32px;
            text-align: center;
        }
        .logo {
            width: 120px;
            margin-bottom: 24px;
        }
        h2 {
            color: #ffffffff;
            margin-bottom: 24px;
        }
        .info {
            color: #cbd5e1;
            margin-bottom: 24px;
        }
        .plan-btn {
            background: #0070f3;
            color: #fff;
            border: none;
            padding: 12px 32px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin: 10px 0;
            display: block;
            width: 100%;
        }
        .plan-btn:hover {
            background: #005bb5;
        }
        a {
            color: #0070f3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        footer {
            text-align: center;
            margin-top: 40px;
            color: #94a3b8;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <div class="center-box">
        <img class="logo" src="Logo_no_background.png" alt="CyberMind Logo">
        <h2>Deine Testphase ist abgelaufen</h2>
        <div class="info">
            Deine kostenlose Testphase (3 Tage) ist beendet.<br>
            Bitte wähle einen Plan, um CyberMind weiter zu nutzen.
        </div>
        <a href="payment_business.php"><button class="plan-btn">Business-Abo kaufen</button></a>
        <a href="payment_solo.php"><button class="plan-btn">Solo-Abo kaufen</button></a>
        <p><a href="logout.php">Logout</a></p>
    </div>
    <footer>
        © 2025 CyberMind – Alle Rechte vorbehalten
    </footer>
</body>
</html>
