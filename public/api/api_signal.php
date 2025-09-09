<?php
require_once __DIR__.'/../../config/config.php';
header('Content-Type: application/json');
$user = $_SESSION['user'] ?? null;
if(!$user){ echo json_encode(['error'=>'Unauthenticated']); exit; }

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $data = json_decode(file_get_contents('php://input'), true);
    $room = $data['room'] ?? '';
    $type = $data['type'] ?? 'offer';
    $payload = $data['payload'] ?? null;
    if(!$room || !$payload){ echo json_encode(['error'=>'Missing']); exit; }
    $stmt = $pdo->prepare("INSERT INTO signals (room_code, sender_id, type, payload) VALUES (?, ?, ?, ?)");
    $stmt->execute([$room, $user['id'], $type, json_encode($payload)]);
    echo json_encode(['success'=>true]); exit;
}

if($_SERVER['REQUEST_METHOD'] === 'GET'){
    $room = $_GET['room'] ?? '';
    $after = intval($_GET['after_id'] ?? 0);
    $stmt = $pdo->prepare("SELECT * FROM signals WHERE room_code = ? AND id > ? ORDER BY id ASC");
    $stmt->execute([$room, $after]);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    foreach($rows as &$r) $r['payload'] = json_decode($r['payload'], true);
    echo json_encode(['signals'=>$rows]); exit;
}
