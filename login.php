<?php
session_start();
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_input = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user_input, $user_input);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $created_at = strtotime($user['created_at']);
            $now = time();
            $days_since = ($now - $created_at) / (60 * 60 * 24);

            if ($days_since > 3 && ($user['plan'] == 'trial' || empty($user['plan']))) {
                header("Location: payment.php");
                exit;
            }

            $_SESSION['username'] = $user['username'];
            if ($user['role'] === 'owner' && $user['plan'] !== 'solo') {
                header("Location: dashboard.php");
                exit;
            } else if ($user['role'] === 'member') {
                header("Location: exercises.php");
                exit;
            } else {
                header("Location: exercises.php");
                exit;
            }
        } else {
            $error = "Falsches Passwort!";
        }
    } else {
        $error = "Benutzername oder E-Mail nicht gefunden!";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
      content="width=device-width, initial-scale=1.0, minimum-scale=0.9, maximum-scale=0.9">
    <title>Login – CyberMind</title>
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
        input[type="text"], input[type="password"] {
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
    </style>
</head>
<body>
    <div class="center-box">
        <img class="logo" src="Logo_no_background.png" alt="CyberMind Logo">
        <h2>Login</h2>

        <form method="POST">
            <input type="text" name="username" id="username" placeholder="Benutzername oder E-Mail" required>
            <input type="password" name="password" placeholder="Passwort" required><br>
            <button type="submit">Login</button>
        </form>
        <p>Noch kein Account? <a href="register.php">Registrieren</a></p>
        <p>Mit Invite Code? <a href="invite_register.php">Hier beitreten</a></p>
    </div>
</body>
</html>
