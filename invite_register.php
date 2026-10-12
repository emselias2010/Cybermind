<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $invite_code = $_POST['invite_code'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    $sql = "SELECT plan FROM users WHERE invite_code = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $invite_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $owner = $result->fetch_assoc();

    if ($owner) {
        $plan = $owner['plan'];
        $max = ($plan == 'school') ? 1000 : 100;

        $sql = "SELECT COUNT(*) as cnt FROM users WHERE invite_code = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $invite_code);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $cnt = $row['cnt'];

        if ($cnt < $max) {
            if ($password !== $confirm_password) {
                $error = "Die Passwörter stimmen nicht überein!";
            } else {
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $role = 'member';
                $sql = "INSERT INTO users (username, email, phone, password, plan, invite_code, role, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssssss", $username, $email, $phone, $password_hash, $plan, $invite_code, $role);
                if ($stmt->execute()) {
                    header("Location: login.php");
                    exit;
                } else {
                    $error = "Fehler: " . $stmt->error;
                }
            }
        } else {
            $error = "Maximale Anzahl an Accounts für diesen Invite-Code erreicht!";
        }
    } else {
        $error = "Ungültiger Invite-Code!";
    }
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
      content="width=device-width, initial-scale=1.0, minimum-scale=0.9, maximum-scale=0.9">
    <title>Mit Invite Code beitreten – CyberMind</title>
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
        a {
            color: #3b82f6;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
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
    </style>
</head>
<body>
    <div class="center-box">
        <img class="logo" src="Logo_no_background.png" alt="CyberMind Logo">
        <h2>Mit Invite Code beitreten</h2>
        <?php if (isset($error)) echo "<div class='error'>$error</div>"; ?>
        <form method="POST">
            <input type="text" name="invite_code" placeholder="Invite Code" required><br>
            <input type="text" name="username" placeholder="Benutzername" required><br>
            <input type="email" name="email" placeholder="E-Mail" required><br>
            <input type="tel" name="phone" placeholder="Handynummer" required><br>
            <input type="password" name="password" placeholder="Passwort" required><br>
            <input type="password" name="confirm_password" placeholder="Passwort bestätigen" required><br>
            <button type="submit">Beitreten</button>
        </form>
        <p>Schon ein Konto? <a href="login.php">Login</a></p>
        <p>Noch kein Invite? <a href="register.php">Registrieren</a></p>
    </div>
</body>
</html>
