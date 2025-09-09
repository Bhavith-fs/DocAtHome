<?php
session_start();
require_once __DIR__ . "/../../config/config.php";

if (!isset($_SESSION['user_id'])) {
    die("Please login first. <a href='../auth/login.php'>Login</a>");
}

$appt_id = $_GET['appt_id'] ?? null;
if (!$appt_id) die("No appointment selected.");

// fetch appointment
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE id=?");
$stmt->execute([$appt_id]);
$appt = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$appt) die("Invalid appointment.");

// check if user is patient or doctor for this appointment
if ($_SESSION['user_id'] != $appt['patient_id'] && $_SESSION['user_id'] != $appt['doctor_id']) {
    die("Access denied.");
}

// handle message sending
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $msg = trim($_POST['message']);
    if ($msg !== "") {
        $from = $_SESSION['user_id'];
        $to = ($_SESSION['user_id'] == $appt['patient_id']) ? $appt['doctor_id'] : $appt['patient_id'];

        $stmt = $pdo->prepare("INSERT INTO messages (appointment_id, from_user, to_user, message) VALUES (?,?,?,?)");
        $stmt->execute([$appt_id, $from, $to, $msg]);
    }
}

// fetch chat messages
$stmt = $pdo->prepare("SELECT * FROM messages WHERE appointment_id=? ORDER BY created_at ASC");
$stmt->execute([$appt_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Chat</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    .chat-box { border:1px solid #ccc; padding:10px; width:400px; height:300px; overflow-y:scroll; margin-bottom:10px; }
    .me { color: green; }
    .other { color: blue; }
  </style>
</head>
<body>
  <h2>Chat</h2>
  <div class="chat-box">
    <?php foreach ($messages as $m): ?>
      <p class="<?= $m['from_user']==$_SESSION['user_id'] ? 'me' : 'other' ?>">
        <b><?= $m['from_user']==$_SESSION['user_id'] ? 'Me' : 'Them' ?>:</b> 
        <?= htmlspecialchars($m['message']) ?>
      </p>
    <?php endforeach; ?>
  </div>

  <form method="post">
    <input type="text" name="message" placeholder="Type a message..." required>
    <button type="submit">Send</button>
  </form>
</body>
</html>
