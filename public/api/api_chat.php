<?php
session_start();
require_once __DIR__ . "/../../config/config.php";

$appt_id = $_GET['appt_id'] ?? null;
if (!$appt_id) { echo json_encode([]); exit; }

$stmt = $pdo->prepare("SELECT * FROM messages WHERE appointment_id=? ORDER BY created_at ASC");
$stmt->execute([$appt_id]);
$messages = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode($messages);
