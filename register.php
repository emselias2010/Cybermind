<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password !== $confirm_password) {
        $error = "Die Passwörter stimmen nicht überein!";
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (username, email, phone, password, created_at) VALUES (?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $email, $phone, $password_hash);

        if ($stmt->execute()) {
            header("Location: login.php");
            exit;
        } else {
            $error = "Fehler: " . $stmt->error;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta name="viewport"
      content="width=device-width, initial-scale=1.0, minimum-scale=0.9, maximum-scale=0.9">
    <meta charset="UTF-8">
    <title>Registrieren – CyberMind</title>
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
            color: #ffffff;
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
            background: #0070f3;
            color: #fff;
            border: none;
            padding: 12px 32px;
            border-radius: 6px;
            font-size: 16px;
            cursor: pointer;
            margin-top: 10px;
        }
        button:hover {
            background: #005bb5;
        }
        a {
            color: #0070f3;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
        .error {
            color: #e74c3c;
            margin-bottom: 16px;
        }
    </style>
</head>
<body>
    <div class="center-box">
        <img class="logo" src="Logo_no_background.png" alt="CyberMind Logo">
        <h2>Registrieren</h2>
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="username" placeholder="Benutzername" required><br>
            <input type="email" name="email" placeholder="E-Mail" required><br>
            <input type="tel" name="phone" placeholder="Handynummer" required><br>
            <input type="password" name="password" placeholder="Passwort" required><br>
            <input type="password" name="confirm_password" placeholder="Passwort bestätigen" required><br>
            <button type="submit">Registrieren</button>
        </form>
        <p>Schon ein Konto? <a href="login.php">Login</a></p>
    </div>
</body>
</html>
</html>
