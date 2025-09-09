<?php
session_start();
require_once __DIR__ . "/../../config/config.php";

// only doctors can see this
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'doctor') {
    die("Access denied. <a href='../auth/login.php'>Login here</a>");
}

// get doctor’s appointments
$stmt = $pdo->prepare("
    SELECT a.*, u.name AS patient_name 
    FROM appointments a 
    JOIN users u ON a.patient_id = u.id 
    WHERE a.doctor_id = ? 
    ORDER BY a.datetime DESC
");
$stmt->execute([$_SESSION['user_id']]);
$appointments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Doctor Dashboard</title>
  <style>
    body { font-family: Arial, sans-serif; margin: 20px; }
    table { border-collapse: collapse; width: 100%; margin-top: 20px; }
    th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
  </style>
</head>
<body>
  <h1>My Patients</h1>
  <table>
    <tr>
      <th>Date & Time</th>
      <th>Patient</th>
      <th>Status</th>
      <th>Action</th>
    </tr>
    <?php if ($appointments): ?>
      <?php foreach ($appointments as $appt): ?>
        <tr>
          <td><?= htmlspecialchars($appt['datetime']) ?></td>
          <td><?= htmlspecialchars($appt['patient_name']) ?></td>
          <td><?= htmlspecialchars($appt['status']) ?></td>
          <td><a href="../patients/chat.php?appt_id=<?= $appt['id'] ?>">Chat</a></td>
        </tr>
      <?php endforeach; ?>
    <?php else: ?>
      <tr><td colspan="4">No appointments found.</td></tr>
    <?php endif; ?>
  </table>
</body>
</html>
