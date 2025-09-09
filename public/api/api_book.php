<?php
require_once __DIR__.'/../../config/config.php';
header('Content-Type: application/json');
if($_SERVER['REQUEST_METHOD'] !== 'POST'){ http_response_code(405); exit; }
if(!isset($_SESSION['user'])){ echo json_encode(['error'=>'Unauthenticated']); exit; }

$data = json_decode(file_get_contents('php://input'), true);
$doctor_id = intval($data['doctor_id'] ?? 0);
$datetime = $data['datetime'] ?? null;
$duration = intval($data['duration'] ?? 15);
if(!$doctor_id || !$datetime){ echo json_encode(['error'=>'Missing data']); exit; }

$room_code = bin2hex(random_bytes(8));
$stmt = $pdo->prepare("INSERT INTO appointments (patient_id, doctor_id, datetime, duration_minutes, room_code) VALUES (?, ?, ?, ?, ?)");
$stmt->execute([$_SESSION['user']['id'], $doctor_id, $datetime, $duration, $room_code]);
echo json_encode(['success'=>true, 'room_code'=>$room_code]);
