<?php
session_start();
include 'db.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

$sql = "SELECT id, plan, created_at FROM users WHERE username=?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $_SESSION['username']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id'];

$created_at = strtotime($user['created_at']);
$now = time();
$days_since = ($now - $created_at) / (60 * 60 * 24);
if ($days_since > 3 && ($user['plan'] == 'trial' || empty($user['plan']))) {
    header("Location: payment.php");
    exit;
}

$course_duration = 12;

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['exercise_id'])) {
    $exercise_id = intval($_POST['exercise_id']);
    $sql = "SELECT id FROM social_exercise_user WHERE user_id=? AND exercise_id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $user_id, $exercise_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $sql = "UPDATE social_exercise_user SET completed=1 WHERE user_id=? AND exercise_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $exercise_id);
        $stmt->execute();
    } else {
        $sql = "INSERT INTO social_exercise_user (user_id, exercise_id, completed) VALUES (?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $user_id, $exercise_id);
        $stmt->execute();
    }

    $sql = "SELECT COUNT(*) as completed_count FROM social_exercise_user WHERE user_id=? AND completed=1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $completed_count = $res->fetch_assoc()['completed_count'];

    $sql = "SELECT COUNT(*) as total_count FROM social_exercises";
    $res = $conn->query($sql);
    $total_count = $res->fetch_assoc()['total_count'];

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        echo json_encode([
            "success" => true,
            "quizUnlocked" => $completed_count >= $total_count
        ]);
        exit;
    }

    header("Location: social_engineering_course.php");
    exit;
}

$sql = "SELECT * FROM social_exercises";
$result = $conn->query($sql);

$exercises = [];
$completed_count = 0;
while ($exercise = $result->fetch_assoc()) {
    $sql2 = "SELECT completed FROM social_exercise_user WHERE user_id=? AND exercise_id=?";
    $stmt2 = $conn->prepare($sql2);
    $stmt2->bind_param("ii", $user_id, $exercise['id']);
    $stmt2->execute();
    $res2 = $stmt2->get_result();
    $done = ($res2->fetch_assoc()['completed'] ?? 0);
    if ($done) $completed_count++;
    $exercise['done'] = $done;
    $exercises[] = $exercise;
}
$total_exercises = count($exercises);
$all_done = ($completed_count === $total_exercises);

$quiz_score = null;
$quiz_done = false;
$quiz_feedback = [];
if ($all_done) {
    $sql = "SELECT * FROM social_quiz_user WHERE user_id=? ORDER BY completed_at DESC LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $quiz_result = $res->fetch_assoc();
    if ($quiz_result) {
        $quiz_score = $quiz_result['score'];
        $quiz_done = true;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['quiz'])) {
    $sql = "SELECT * FROM social_quiz";
    $quiz = $conn->query($sql);
    $score = 0;
    $quiz_feedback = [];
    while ($q = $quiz->fetch_assoc()) {
        $qid = $q['id'];
        $correct = $q['correct'];
        $user_answer = $_POST['quiz_'.$qid] ?? '';
        $is_correct = ($user_answer === $correct);
        if ($is_correct) $score++;
        $quiz_feedback[] = [
            "question" => $q['question'],
            "your_answer" => $user_answer,
            "your_text" => $q['answer_'.$user_answer] ?? '',
            "correct_answer" => $q['answer_'.$correct],
            "correct" => $is_correct
        ];
    }
    $stmt = $conn->prepare("INSERT INTO social_quiz_user (user_id, score, completed_at) VALUES (?, ?, NOW())");
    $stmt->bind_param("ii", $user_id, $score);
    $stmt->execute();
    $quiz_score = $score;
    $quiz_done = true;
}
?>
<!DOCTYPE html>
<html lang="de">
<head>
<meta name="viewport"
  content="width=device-width, initial-scale=1.0, minimum-scale=0.9, maximum-scale=0.9">
<meta charset="UTF-8">
<title>Social Engineering Kurs – CyberMind</title>
<link rel="icon" type="image/png" href="Icon.png">
<style>
body {background: linear-gradient(135deg, #0b1630ff, #1e293b); font-family: 'Segoe UI', Arial, sans-serif; margin:0; padding:0; color:#fff;}
.center-box {max-width:600px; margin:60px auto; background:#334155; border-radius:16px; padding:40px 32px;}
.logo {width:120px; display:block; margin:0 auto 24px auto;}
h2 {text-align:center;}
.duration {text-align:center; color:#94a3b8; margin-bottom:24px;}
.progress-bar {background:#1e293b; border-radius:8px; height:18px; margin-bottom:24px; overflow:hidden;}
.progress {background:#3b82f6; height:100%; width: <?php echo $total_exercises ? intval(($completed_count/$total_exercises)*100) : 0; ?>%; transition: width 0.4s;}
.exercise-list {list-style:none; padding:0;}
.exercise-list li {background:#475569; margin-bottom:12px; padding:18px 16px; border-radius:8px;}
.exercise-list .done {color:#16a34a; font-weight:bold;}
.exercise-list button {background:#3b82f6; color:#fff; border:none; padding:8px 18px; border-radius:6px; cursor:pointer;}
.exercise-list button:hover {background:#2563eb;}
.quiz-section {margin-top:32px; background:#475569; border-radius:8px; padding:24px; display: <?php echo $all_done ? "block" : "none"; ?>;}
.quiz-result {color:#16a34a; font-weight:bold;}
.quiz-feedback {margin-top:16px; padding:12px; border-top:1px solid #64748b;}
.quiz-feedback div {margin-bottom:8px;}
.quiz-section button {background:#3b82f6; color:#fff; border:none; padding:8px 18px; border-radius:6px; font-size:15px; cursor:pointer; display:block; margin:12px auto 0 auto;}
.quiz-section button:hover {background:#2563eb;}
a {color:#3b82f6; text-decoration:none;}
a:hover {text-decoration:underline;}
footer {text-align:center; margin-top:40px; color:#94a3b8; font-size:15px;}
</style>
</head>
<body>
<div class="center-box">
<img class="logo" src="Logo_no_background.png" alt="CyberMind Logo">
<h2>Social Engineering Kurs</h2>
<div class="duration">⏱️ Dauer: ca. <?php echo $course_duration; ?> Minuten</div>
<div class="progress-bar"><div class="progress" id="progress"></div></div>

<h3>Übungen</h3>
<ul class="exercise-list" id="exerciseList">
<?php foreach ($exercises as $ex): ?>
<li data-id="<?php echo $ex['id']; ?>">
<b><?php echo htmlspecialchars($ex['title']); ?></b><br>
<span><?php echo htmlspecialchars($ex['content']); ?></span>
<?php if ($ex['done']): ?>
<span class="done">✔ Abgeschlossen</span>
<?php else: ?>
<form method="POST" class="exerciseForm" style="margin-top:8px;">
<input type="hidden" name="exercise_id" value="<?php echo $ex['id']; ?>">
<button type="submit">Abschließen</button>
</form>
<?php endif; ?>
</li>
<?php endforeach; ?>
</ul>

<div class="quiz-section" id="quizSection">
<h3>Quiz zum Kurs</h3>
<?php if ($quiz_done): ?>
<div class="quiz-result">Quiz abgeschlossen! Punkte: <?php echo $quiz_score; ?></div>
<div class="quiz-feedback">
<?php foreach ($quiz_feedback as $qf): ?>
<div>
<b><?php echo htmlspecialchars($qf['question']); ?></b><br>
Deine Antwort: <?php echo htmlspecialchars($qf['your_text']); ?> –
<span style="color:<?php echo $qf['correct'] ? '#10b981' : '#f87171'; ?>"><?php echo $qf['correct'] ? '✔ Richtig' : '✖ Falsch'; ?></span><br>
Richtige Antwort: <?php echo htmlspecialchars($qf['correct_answer']); ?>
</div>
<?php endforeach; ?>
</div>
<?php else: ?>
<form method="POST">
<?php
$sql = "SELECT * FROM social_quiz";
$quiz = $conn->query($sql);
while ($q = $quiz->fetch_assoc()):
?>
<div style="margin-bottom:16px;">
<b><?php echo htmlspecialchars($q['question']); ?></b><br>
<label><input type="radio" name="quiz_<?php echo $q['id']; ?>" value="a" required> <?php echo htmlspecialchars($q['answer_a']); ?></label><br>
<label><input type="radio" name="quiz_<?php echo $q['id']; ?>" value="b"> <?php echo htmlspecialchars($q['answer_b']); ?></label><br>
<label><input type="radio" name="quiz_<?php echo $q['id']; ?>" value="c"> <?php echo htmlspecialchars($q['answer_c']); ?></label>
</div>
<?php endwhile; ?>
<input type="hidden" name="quiz" value="1">
<button type="submit">Quiz abschließen</button>
</form>
<?php endif; ?>
</div>

<br><a href="dashboard.php">Zurück zum Dashboard</a>
</div>
<footer>© 2025 CyberMind – Alle Rechte vorbehalten</footer>

<script>
document.querySelectorAll(".exerciseForm").forEach(form => {
form.addEventListener("submit", function(e) {
    e.preventDefault();
    let formData = new FormData(this);
    fetch("social_engineering_course.php", {
        method: "POST",
        body: formData,
        headers: {"X-Requested-With": "XMLHttpRequest"}
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            let li = this.closest("li");
            this.remove();
            li.insertAdjacentHTML("beforeend", '<span class="done">✔ Abgeschlossen</span>');
            let doneCount = document.querySelectorAll(".exercise-list .done").length;
            let total = document.querySelectorAll(".exercise-list li").length;
            document.getElementById("progress").style.width = Math.round((doneCount/total)*100) + "%";
            if (data.quizUnlocked) document.getElementById("quizSection").style.display = "block";
        }
    });
});
});
</script>
</body>
</html>
