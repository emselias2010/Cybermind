<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT role, plan, created_at FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
if (!$user || $user['role'] !== 'owner' || $user['plan'] === 'solo') {
    header("Location: exercises.php");
    exit;
}

$created_at = strtotime($user['created_at']);
$now = time();
$days_since = ($now - $created_at) / (60 * 60 * 24);
if ($days_since > 3 && ($user['plan'] == 'trial' || empty($user['plan']))) {
    header("Location: payment.php");
    exit;
}

$username = $_SESSION['username'];

$sql = "SELECT invite_code FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$invite_code = $user['invite_code'] ?? '';

$kursdaten = [
    ['exercise_table' => 'phishing_exercises', 'user_table' => 'phishing_exercise_user'],
    ['exercise_table' => 'social_exercises', 'user_table' => 'social_exercise_user'],
    ['exercise_table' => 'password_exercises', 'user_table' => 'password_exercise_user'],
    ['exercise_table' => 'malware_exercises', 'user_table' => 'malware_exercise_user'],
    ['exercise_table' => 'datenschutz_exercises', 'user_table' => 'datenschutz_exercise_user'],
    ['exercise_table' => 'sicheres_surfen_exercises', 'user_table' => 'sicheres_surfen_exercise_user'],
    ['exercise_table' => 'mobile_security_exercises', 'user_table' => 'mobile_security_exercise_user'],
    ['exercise_table' => 'social_media_exercises', 'user_table' => 'social_media_exercise_user'],
    ['exercise_table' => 'cloud_security_exercises', 'user_table' => 'cloud_security_exercise_user']
];

$total_exercises = 0;
$total_done = 0;
foreach ($kursdaten as $kurs) {
    $sql_total = "SELECT COUNT(*) as total FROM {$kurs['exercise_table']}";
    $res_total = $conn->query($sql_total);
    $total_exercises += $res_total->fetch_assoc()['total'];

    $sql_done = "SELECT COUNT(*) as done FROM {$kurs['user_table']} WHERE user_id=(SELECT id FROM users WHERE username=?) AND completed=1";
    $stmt_done = $conn->prepare($sql_done);
    $stmt_done->bind_param("s", $username);
    $stmt_done->execute();
    $res_done = $stmt_done->get_result();
    $total_done += $res_done->fetch_assoc()['done'];
}
$percent = $total_exercises ? intval($total_done / $total_exercises * 100) : 0;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view_user_id'])) {
    $view_user_id = intval($_POST['view_user_id']);
    $sql = "SELECT username FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $view_user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $view_user = $res->fetch_assoc();

    $kurse = [
        [
            'name' => 'Phishing',
            'exercise_table' => 'phishing_exercises',
            'user_table' => 'phishing_exercise_user',
            'quiz_user_table' => 'phishing_quiz_user',
            'quiz_table' => 'phishing_quiz'
        ],
        [
            'name' => 'Social Engineering',
            'exercise_table' => 'social_exercises',
            'user_table' => 'social_exercise_user',
            'quiz_user_table' => 'social_quiz_user',
            'quiz_table' => 'social_quiz'
        ],
        [
            'name' => 'Passwortsicherheit',
            'exercise_table' => 'password_exercises',
            'user_table' => 'password_exercise_user',
            'quiz_user_table' => 'password_quiz_user',
            'quiz_table' => 'password_quiz'
        ],
        [
            'name' => 'Malware & Viren',
            'exercise_table' => 'malware_exercises',
            'user_table' => 'malware_exercise_user',
            'quiz_user_table' => 'malware_quiz_user',
            'quiz_table' => 'malware_quiz'
        ],
        [
            'name' => 'Datenschutz',
            'exercise_table' => 'datenschutz_exercises',
            'user_table' => 'datenschutz_exercise_user',
            'quiz_user_table' => 'datenschutz_quiz_user',
            'quiz_table' => 'datenschutz_quiz'
        ],
        [
            'name' => 'Sicheres Surfen',
            'exercise_table' => 'sicheres_surfen_exercises',
            'user_table' => 'sicheres_surfen_exercise_user',
            'quiz_user_table' => 'sicheres_surfen_quiz_user',
            'quiz_table' => 'sicheres_surfen_quiz'
        ],
        [
            'name' => 'Mobile Sicherheit',
            'exercise_table' => 'mobile_security_exercises',
            'user_table' => 'mobile_security_exercise_user',
            'quiz_user_table' => 'mobile_security_quiz_user',
            'quiz_table' => 'mobile_security_quiz'
        ],
        [
            'name' => 'Social Media Sicherheit',
            'exercise_table' => 'social_media_exercises',
            'user_table' => 'social_media_exercise_user',
            'quiz_user_table' => 'social_media_quiz_user',
            'quiz_table' => 'social_media_quiz'
        ],
        [
            'name' => 'Cloud Sicherheit',
            'exercise_table' => 'cloud_security_exercises',
            'user_table' => 'cloud_security_exercise_user',
            'quiz_user_table' => 'cloud_security_quiz_user',
            'quiz_table' => 'cloud_security_quiz'
        ]
    ];

    echo '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mitarbeiter-Statistiken – CyberMind</title>
    <link rel="icon" type="image/png" href="Icon.png">
    <style>
        body { background: linear-gradient(135deg, #0b1630ff, #1e293b); font-family: "Segoe UI", Arial, sans-serif; margin:0; color:#fff; }
        .center-box { max-width: 500px; margin: 60px auto; background: #334155; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.15); padding: 40px 32px; }
        h2 { color: #fff; text-align: center; margin-bottom: 18px; }
        .stat-table { width:100%; border-collapse:collapse; margin-top:24px; }
        .stat-table th, .stat-table td { padding:10px 8px; text-align:left; }
        .stat-table th { background:#475569; color:#3b82f6; }
        .stat-table td { background:#475569; color:#fff; }
        .progress-bar { background: #1e293b; border-radius: 8px; height: 16px; overflow: hidden; width: 100px; display:inline-block; vertical-align:middle; margin-right:8px;}
        .progress { background: #3b82f6; height: 100%; transition: width 0.4s; }
        .back-link { display: block; margin-top: 24px; text-align: center; color: #3b82f6; font-size: 16px; }
        .back-link:hover { text-decoration: underline; }
        footer { text-align:center; margin-top:40px; color:#94a3b8; font-size:15px; }
    </style>
</head>
<body>
<header style="display:flex;justify-content:space-between;align-items:center;padding:18px 40px 0 40px;">
    <img src="Logo_no_background.png" alt="CyberMind Logo" style="height:48px;">
</header>
<div class="center-box">
    <h2>Statistiken für ' . htmlspecialchars($view_user['username']) . '</h2>
    <table class="stat-table">
        <tr>
            <th>Kurs</th>
            <th>Fortschritt</th>
            <th>Quizpunkte</th>
        </tr>';
    foreach ($kurse as $kurs) {
        $sql_total = "SELECT COUNT(*) as total FROM {$kurs['exercise_table']}";
        $total = $conn->query($sql_total)->fetch_assoc()['total'];
        $sql_done = "SELECT COUNT(*) as done FROM {$kurs['user_table']} WHERE user_id=? AND completed=1";
        $stmt = $conn->prepare($sql_done);
        $stmt->bind_param("i", $view_user_id);
        $stmt->execute();
        $done = $stmt->get_result()->fetch_assoc()['done'];
        $percent = $total ? intval($done/$total*100) : 0;

        $sql_quiz = "SELECT score FROM {$kurs['quiz_user_table']} WHERE user_id=? ORDER BY completed_at DESC LIMIT 1";
        $stmt = $conn->prepare($sql_quiz);
        $stmt->bind_param("i", $view_user_id);
        $stmt->execute();
        $score = $stmt->get_result()->fetch_assoc()['score'] ?? 0;

        $sql_max = "SELECT COUNT(*) as max_points FROM {$kurs['quiz_table']}";
        $max_points = $conn->query($sql_max)->fetch_assoc()['max_points'] ?? 0;

        echo '<tr>
            <td>' . htmlspecialchars($kurs['name']) . '</td>
            <td>
                <div class="progress-bar"><div class="progress" style="width:' . $percent . '%;"></div></div>
                ' . $done . ' / ' . $total . ' (' . $percent . '%)
            </td>
            <td>' . $score . ' / ' . $max_points . '</td>
        </tr>';
    }
    echo '</table>
    <a href="dashboard.php" class="back-link">Zurück zum Dashboard</a>
</div>
<footer>© 2025 CyberMind – Alle Rechte vorbehalten</footer>
</body>
</html>';
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['remove_id'])) {
    $remove_id = intval($_POST['remove_id']);
    $sql = "DELETE FROM users WHERE id=? AND invite_code=? AND role='member'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $remove_id, $invite_code);
    $stmt->execute();
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Dashboard – CyberMind</title>
    <meta name="viewport"
      content="width=device-width, initial-scale=1.0, minimum-scale=0.9, maximum-scale=0.9">
    <link rel="icon" type="image/png" href="assets/img/Icon.png">
    <style>
        body {
            background: linear-gradient(135deg, #0b1630ff, #1e293b);
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
        }
        .center-box {
            max-width: 600px;
            margin: 60px auto;
            background: #334155;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
            padding: 40px 32px;
            text-align: left;
        }
        h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 18px;
        }
        .invite-box {
            background: #475569;
            border-radius: 12px;
            padding: 18px;
            margin-bottom: 28px;
            text-align: center;
            font-size: 17px;
            color: #cbd5e1;
        }
        .progress-section {
            background: #475569;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 28px;
            text-align: center;
        }
        .progress-bar {
            background: #1e293b;
            border-radius: 8px;
            height: 22px;
            margin: 18px auto 8px auto;
            overflow: hidden;
            width: 80%;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
        }
        .progress {
            background: #3b82f6;
            height: 100%;
            transition: width 0.4s;
        }
        .progress-label {
            color: #cbd5e1;
            font-size: 16px;
            margin-bottom: 6px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        li {
            background: #475569;
            margin-bottom: 12px;
            padding: 18px 16px;
            border-radius: 8px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .user-info {
            flex: 1;
            font-size: 17px;
        }
        .user-actions {
            display: flex;
            gap: 8px;
        }
        button {
            background: #3b82f6;
            color: #fff;
            border: none;
            padding: 8px 18px;
            border-radius: 6px;
            font-size: 15px;
            cursor: pointer;
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
        footer {
            text-align: center;
            margin-top: 40px;
            color: #94a3b8;
            font-size: 15px;
        }
    </style>
</head>
<body>
    <header style="display:flex;justify-content:space-between;align-items:center;padding:18px 40px 0 40px;">
        <img src="Logo_no_background.png" alt="CyberMind Logo" style="height:80px;">
        <div style="position:relative;">
            <button id="profileBtn" style="background:#475569;color:#fff;border:none;padding:10px 22px;border-radius:24px;font-size:16px;cursor:pointer;">
                <?php echo htmlspecialchars($username); ?> &#x25BC;
            </button>
            <div id="profileMenu" style="display:none;position:absolute;right:0;top:48px;background:#334155;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.15);padding:18px 0;min-width:180px;z-index:10;">
                <a href="index.php" style="display:block;padding:10px 24px;color:#fff;text-decoration:none;">Startseite</a>
                <a href="dashboard.php" style="display:block;padding:10px 24px;color:#fff;text-decoration:none;">Dashboard</a>
                <a href="exercises.php" style="display:block;padding:10px 24px;color:#fff;text-decoration:none;">Übungen</a>
                <a href="logout.php" style="display:block;padding:10px 24px;color:#e74c3c;text-decoration:none;">Logout</a>
            </div>
        </div>
    </header>
    <script>
    document.getElementById('profileBtn').onclick = function() {
        var menu = document.getElementById('profileMenu');
        menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
    };
    window.onclick = function(event) {
        if (!event.target.matches('#profileBtn')) {
            document.getElementById('profileMenu').style.display = 'none';
        }
    };
    </script>
    <div class="center-box">
        <h2>Dein Dashboard</h2>
        <div class="invite-box">
            <b>Dein Invite-Code:</b> <span style="color:#3b82f6;"><?php echo htmlspecialchars($invite_code); ?></span>
            <br>
            <span style="font-size:15px;">Teile diesen Code, um Mitarbeiter einzuladen.</span>
        </div>
        <div class="progress-section">
            <div class="progress-label">Dein Gesamter Kursfortschritt</div>
            <div class="progress-bar">
                <div class="progress" style="width:<?php echo $percent; ?>%;"></div>
            </div>
            <div style="color:#cbd5e1;font-size:15px;">
                <?php echo $total_done; ?> von <?php echo $total_exercises; ?> Übungen abgeschlossen (<?php echo $percent; ?>%)
            </div>
        </div>
        <h2>Deine eingeladenen Nutzer</h2>
        <ul>
        <?php
        $sql = "SELECT id, username FROM users WHERE invite_code=? AND role='member'";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $invite_code);
        $stmt->execute();
        $result = $stmt->get_result();

        $sql_self = "SELECT id FROM users WHERE username=?";
        $stmt_self = $conn->prepare($sql_self);
        $stmt_self->bind_param("s", $username);
        $stmt_self->execute();
        $res_self = $stmt_self->get_result();
        $self = $res_self->fetch_assoc();
        $self_id = $self['id'];

        foreach ($result as $row) {
            $member_total = 0;
            $member_done = 0;
            foreach ($kursdaten as $kurs) {
                $sql_total = "SELECT COUNT(*) as total FROM {$kurs['exercise_table']}";
                $res_total = $conn->query($sql_total);
                $member_total += $res_total->fetch_assoc()['total'];

                $sql_done = "SELECT COUNT(*) as done FROM {$kurs['user_table']} WHERE user_id=? AND completed=1";
                $stmt_done = $conn->prepare($sql_done);
                $stmt_done->bind_param("i", $row['id']);
                $stmt_done->execute();
                $res_done = $stmt_done->get_result();
                $member_done += $res_done->fetch_assoc()['done'];
            }
            $member_percent = $member_total ? intval($member_done / $member_total * 100) : 0;

            echo "<li><div class='user-info'><b>{$row['username']}</b>
                <div style='margin-top:8px;'>
                    <div style='background:#1e293b;border-radius:8px;height:18px;width:80%;margin:auto;overflow:hidden;'>
                        <div style='background:#3b82f6;height:100%;width:{$member_percent}%;transition:width 0.4s;'></div>
                    </div>
                    <div style='color:#cbd5e1;font-size:15px;margin-top:4px;'>{$member_done} von {$member_total} Übungen abgeschlossen ({$member_percent}%)</div>
                </div>
            </div>
            <div class='user-actions'>";
            if ($row['id'] != $self_id) {
                echo "<form method='POST' style='display:inline'>
                        <input type='hidden' name='view_user_id' value='{$row['id']}'>
                        <button type='submit'>Statistiken</button>
                    </form>
                    <form method='POST' style='display:inline'>
                        <input type='hidden' name='remove_id' value='{$row['id']}'>
                        <button type='submit'>Entfernen</button>
                    </form>";
            }
            echo "</div></li>";
        }
        ?>
        </ul>
    </div>
    <footer>
        © 2025 CyberMind – Alle Rechte vorbehalten
    </footer>
</body>
</html>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['view_user_id'])) {
    $view_user_id = intval($_POST['view_user_id']);
    $sql = "SELECT username FROM users WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $view_user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $view_user = $res->fetch_assoc();

    $kurse = [
        ['name' => 'Phishing', 'exercise_table' => 'phishing_exercises', 'user_table' => 'phishing_exercise_user', 'quiz_user_table' => 'phishing_quiz_user', 'quiz_table' => 'phishing_quiz'],
        ['name' => 'Social Engineering', 'exercise_table' => 'social_exercises', 'user_table' => 'social_exercise_user', 'quiz_user_table' => 'social_quiz_user', 'quiz_table' => 'social_quiz'],
        ['name' => 'Passwortsicherheit', 'exercise_table' => 'password_exercises', 'user_table' => 'password_exercise_user', 'quiz_user_table' => 'password_quiz_user', 'quiz_table' => 'password_quiz'],
        ['name' => 'Malware & Viren', 'exercise_table' => 'malware_exercises', 'user_table' => 'malware_exercise_user', 'quiz_user_table' => 'malware_quiz_user', 'quiz_table' => 'malware_quiz'],
        ['name' => 'Datenschutz', 'exercise_table' => 'datenschutz_exercises', 'user_table' => 'datenschutz_exercise_user', 'quiz_user_table' => 'datenschutz_quiz_user', 'quiz_table' => 'datenschutz_quiz'],
        ['name' => 'Sicheres Surfen', 'exercise_table' => 'sicheres_surfen_exercises', 'user_table' => 'sicheres_surfen_exercise_user', 'quiz_user_table' => 'sicheres_surfen_quiz_user', 'quiz_table' => 'sicheres_surfen_quiz'],
        ['name' => 'Mobile Sicherheit', 'exercise_table' => 'mobile_security_exercises', 'user_table' => 'mobile_security_exercise_user', 'quiz_user_table' => 'mobile_security_quiz_user', 'quiz_table' => 'mobile_security_quiz'],
        ['name' => 'Social Media Sicherheit', 'exercise_table' => 'social_media_exercises', 'user_table' => 'social_media_exercise_user', 'quiz_user_table' => 'social_media_quiz_user', 'quiz_table' => 'social_media_quiz'],
        ['name' => 'Cloud Sicherheit', 'exercise_table' => 'cloud_security_exercises', 'user_table' => 'cloud_security_exercise_user', 'quiz_user_table' => 'cloud_security_quiz_user', 'quiz_table' => 'cloud_security_quiz']
    ];

    echo '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Mitarbeiter-Statistiken – CyberMind</title>
    <link rel="icon" type="image/png" href="assets/img/Icon.png">
    <style>
        body { background: linear-gradient(135deg, #0b1630ff, #1e293b); font-family: "Segoe UI", Arial, sans-serif; margin:0; color:#fff; }
        .center-box { max-width: 500px; margin: 60px auto; background: #334155; border-radius: 16px; box-shadow: 0 4px 24px rgba(0,0,0,0.15); padding: 40px 32px; }
        h2 { color: #fff; text-align: center; margin-bottom: 18px; }
        .stat-table { width:100%; border-collapse:collapse; margin-top:24px; }
        .stat-table th, .stat-table td { padding:10px 8px; text-align:left; }
        .stat-table th { background:#475569; color:#3b82f6; }
        .stat-table td { background:#475569; color:#fff; }
        .progress-bar { background: #1e293b; border-radius: 8px; height: 16px; overflow: hidden; width: 100px; display:inline-block; vertical-align:middle; margin-right:8px;}
        .progress { background: #3b82f6; height: 100%; transition: width 0.4s; }
        .back-link { display: block; margin-top: 24px; text-align: center; color: #3b82f6; font-size: 16px; }
        .back-link:hover { text-decoration: underline; }
        footer { text-align:center; margin-top:40px; color:#94a3b8; font-size:15px; }
    </style>
</head>
<body>
<header style="display:flex;justify-content:space-between;align-items:center;padding:18px 40px 0 40px;">
    <img src="Logo_no_background.png" alt="CyberMind Logo" style="height:48px;">
</header>
<div class="center-box">
    <h2>Statistiken für ' . htmlspecialchars($view_user['username']) . '</h2>
    <table class="stat-table">
        <tr>
            <th>Kurs</th>
            <th>Fortschritt</th>
            <th>Quizpunkte</th>
        </tr>';
    foreach ($kurse as $kurs) {
        $sql_total = "SELECT COUNT(*) as total FROM {$kurs['exercise_table']}";
        $total = $conn->query($sql_total)->fetch_assoc()['total'];
        $sql_done = "SELECT COUNT(*) as done FROM {$kurs['user_table']} WHERE user_id=? AND completed=1";
        $stmt = $conn->prepare($sql_done);
        $stmt->bind_param("i", $view_user_id);
        $stmt->execute();
        $done = $stmt->get_result()->fetch_assoc()['done'];
        $percent = $total ? intval($done/$total*100) : 0;

        $sql_quiz = "SELECT score FROM {$kurs['quiz_user_table']} WHERE user_id=? ORDER BY completed_at DESC LIMIT 1";
        $stmt = $conn->prepare($sql_quiz);
        $stmt->bind_param("i", $view_user_id);
        $stmt->execute();
        $score = $stmt->get_result()->fetch_assoc()['score'] ?? 0;

        $sql_max = "SELECT COUNT(*) as max_points FROM {$kurs['quiz_table']}";
        $max_points = $conn->query($sql_max)->fetch_assoc()['max_points'] ?? 0;

        echo '<tr>
            <td>' . htmlspecialchars($kurs['name']) . '</td>
            <td>
                <div class="progress-bar"><div class="progress" style="width:' . $percent . '%;"></div></div>
                ' . $done . ' / ' . $total . ' (' . $percent . '%)
            </td>
            <td>' . $score . ' / ' . $max_points . '</td>
        </tr>';
    }
    echo '</table>
    <a href="dashboard.php" class="back-link">Zurück zum Dashboard</a>
</div>
<footer>© 2025 CyberMind – Alle Rechte vorbehalten</footer>
</body>
</html>';
    exit;
}
