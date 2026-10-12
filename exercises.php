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
if (!$user) {
    header("Location: login.php");
    exit;
}

$created_at = strtotime($user['created_at']);
$now = time();
$days_since = ($now - $created_at) / (60 * 60 * 24);
if ($days_since > 3 && ($user['plan'] == 'trial' || empty($user['plan']))) {
    header("Location: payment.php");
    exit;
}

$courses = [
    [
        'file' => 'phishing_course.php',
        'title' => 'Phishing erkennen',
        'desc' => 'Lerne, wie du Phishing-Mails erkennst und dich schützt.',
        'duration' => '10 Minuten'
    ],
    [
        'file' => 'password_course.php',
        'title' => 'Passwortsicherheit',
        'desc' => 'Alles über sichere Passwörter und Passwort-Manager.',
        'duration' => '8 Minuten'
    ],
    [
        'file' => 'social_engineering_course.php',
        'title' => 'Social Engineering',
        'desc' => 'Schütze dich vor Manipulation und Betrugsversuchen.',
        'duration' => '12 Minuten'
    ],
    [
        'file' => 'malware_course.php',
        'title' => 'Malware & Viren',
        'desc' => 'Erkenne und vermeide Schadsoftware.',
        'duration' => '10 Minuten'
    ],
    [
        'file' => 'datenschutz_course.php',
        'title' => 'Datenschutz',
        'desc' => 'Lerne, wie du persönliche Daten schützt.',
        'duration' => '12 Minuten'
    ],
    [
        'file' => 'sicheres_surfen_course.php',
        'title' => 'Sicheres Surfen',
        'desc' => 'Tipps für sicheres Verhalten im Internet.',
        'duration' => '8 Minuten'
    ],
    [
        'file' => 'mobile_security_course.php',
        'title' => 'Mobile Sicherheit',
        'desc' => 'Schütze dein Smartphone und Tablet.',
        'duration' => '10 Minuten'
    ],
    [
        'file' => 'social_media_course.php',
        'title' => 'Social Media Sicherheit',
        'desc' => 'Sicherer Umgang mit sozialen Netzwerken.',
        'duration' => '12 Minuten'
    ],
    [
        'file' => 'cloud_security_course.php',
        'title' => 'Cloud Sicherheit',
        'desc' => 'Sicherer Umgang mit Cloud-Diensten.',
        'duration' => '8 Minuten'
    ]
];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['exercise_id'])) {
    $exercise_id = intval($_POST['exercise_id']);
    if (isset($_POST['ajax'])) {
        exit;
    }
    header("Location: phishing_course.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>Kurse – CyberMind</title>
    <meta name="viewport"
      content="width=device-width, initial-scale=1.0, minimum-scale=0.9, maximum-scale=0.9">
    <link rel="icon" type="image/png" href="Icon.png">
    <style>
        body {
            background: linear-gradient(135deg, #0b1630ff, #1e293b);
            font-family: 'Segoe UI', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #fff;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 40px 0 40px;
        }
        .logo {
            height: 80px;
        }
        .center-box {
            max-width: 700px;
            margin: 40px auto;
            background: #334155;
            border-radius: 16px;
            box-shadow: 0 4px 24px rgba(0,0,0,0.15);
            padding: 40px 32px;
            text-align: left;
        }
        h2 {
            color: #fff;
            text-align: center;
            margin-bottom: 28px;
        }
        .course-list {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 24px;
        }
        .course-card {
            background: #475569;
            border-radius: 12px;
            padding: 28px 22px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.10);
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .course-title {
            font-size: 1.2rem;
            font-weight: bold;
            margin-bottom: 10px;
            color: #3b82f6;
        }
        .course-desc {
            color: #cbd5e1;
            margin-bottom: 18px;
        }
        .course-duration {
            color: #94a3b8;
            font-size: 15px;
            margin-bottom: 18px;
        }
        .course-btn {
            background: #10b981;
            color: #fff;
            border: none;
            padding: 10px 28px;
            border-radius: 8px;
            font-size: 16px;
            cursor: pointer;
            margin-top: auto;
        }
        .course-btn:hover {
            background: #059669;
        }
        .profile-menu {
            position:relative;
        }
        #profileBtn {
            background:#475569;color:#fff;border:none;padding:10px 22px;border-radius:24px;font-size:16px;cursor:pointer;
        }
        #profileMenu {
            display:none;position:absolute;right:0;top:48px;background:#334155;border-radius:12px;box-shadow:0 4px 16px rgba(0,0,0,0.15);padding:18px 0;min-width:180px;z-index:10;
        }
        #profileMenu a {
            display:block;padding:10px 24px;color:#fff;text-decoration:none;
        }
        #profileMenu a:last-child { color:#e74c3c; }
        #profileMenu a:hover { background:#475569; }
        footer {
            text-align: center;
            margin-top: 40px;
            color: #94a3b8;
            font-size: 15px;
        }
        @media (max-width: 900px) {
            .course-list { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    <header>
        <img class="logo" src="Logo_no_background.png" alt="CyberMind Logo">
        <div class="profile-menu">
            <button id="profileBtn"><?php echo htmlspecialchars($_SESSION['username']); ?> &#x25BC;</button>
            <div id="profileMenu">
                <a href="index.php">Startseite</a>
                <?php if ($user['role'] === 'owner' && $user['plan'] !== 'solo'): ?>
                    <a href="dashboard.php">Dashboard</a>
                <?php endif; ?>
                <a href="exercises.php">Kurse</a>
                <a href="logout.php">Logout</a>
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
    document.querySelectorAll('.exercise-form').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            var exid = form.getAttribute('data-exid');
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'phishing_course.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    form.outerHTML = '<span class="done">✔ Abgeschlossen</span>';
                }
            };
            xhr.send('exercise_id=' + encodeURIComponent(exid) + '&ajax=1');
        });
    });
    </script>
    <div class="center-box">
        <h2>Wähle deinen Kurs</h2>
        <div class="course-list">
            <?php foreach ($courses as $course): ?>
                <div class="course-card">
                    <div class="course-title"><?php echo htmlspecialchars($course['title']); ?></div>
                    <div class="course-desc"><?php echo htmlspecialchars($course['desc']); ?></div>
                    <div class="course-duration">⏱️ Dauer: <?php echo htmlspecialchars($course['duration']); ?></div>
                    <a href="<?php echo $course['file']; ?>"><button class="course-btn">Kurs starten</button></a>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <footer>
        © 2025 CyberMind – Alle Rechte vorbehalten
    </footer>
</body>
</html>
