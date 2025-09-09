<?php
session_start();
require_once __DIR__ . "/../../config/config.php";

// make sure only patients can book
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'patient') {
    die("Only patients can book appointments. <a href='../auth/login.php'>Login here</a>");
}

$doctor_id = $_GET['doctor_id'] ?? null;

if (!$doctor_id) {
    die("No doctor selected. <a href='../index.php'>Go back</a>");
}

// if form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $datetime = $_POST['datetime'];

    $stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, datetime, status) VALUES (?, ?, ?, 'pending')");
    $stmt->execute([$_SESSION['user_id'], $doctor_id, $datetime]);

    echo "✅ Appointment booked successfully! <a href='dashboard.php'>Go to Dashboard</a>";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Book Appointment</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    form { margin-top: 20px; }
    input, button { padding: 6px; margin-top: 8px; }
  </style>
</head>
<body>
  <h2>Book Appointment</h2>
  <form method="post">
    <label>Select Date & Time:</label><br>
    <input type="datetime-local" name="datetime" required><br><br>
    <button type="submit">Confirm Booking</button>
  </form>
</body>
</html>
