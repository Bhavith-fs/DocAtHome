<?php
require_once __DIR__.'/../../config/config.php';
if(!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'patient') header('Location: /docathome/public/auth/login.php');
$uid = $_SESSION['user']['id'];
$stmt = $pdo->prepare("SELECT a.*, u.name AS doctor_name FROM appointments a JOIN users u ON a.doctor_id = u.id WHERE a.patient_id = ? ORDER BY a.datetime DESC");
$stmt->execute([$uid]);
$apps = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html><html><head><script src="https://cdn.tailwindcss.com"></script></head><body><?php include __DIR__.'/../partials/header.php'; ?>
<main class="max-w-4xl mx-auto p-6">
  <h2 class="text-xl mb-4">Your appointments</h2>
  <div class="space-y-4">
  <?php foreach($apps as $a): ?>
    <div class="bg-white p-4 rounded shadow">
      <div class="flex justify-between">
        <div>
          <div class="font-semibold"><?= e($a['doctor_name']) ?></div>
          <div class="text-sm text-gray-600"><?= e($a['datetime']) ?> (<?= e($a['duration_minutes']) ?> mins)</div>
        </div>
        <div class="text-right">
          <div class="text-sm"><?= e($a['status']) ?></div>
          <?php if($a['status'] !== 'cancelled'): ?>
            <a class="inline-block mt-2 btn-primary" href="/docathome/public/patients/chat.php?appointment=<?= $a['id'] ?>&doctor=<?= $a['doctor_id'] ?>">Chat</a>
            <a class="inline-block mt-2 ml-2" href="/docathome/public/patients/chat.php?appointment=<?= $a['id'] ?>&doctor=<?= $a['doctor_id'] ?>&call=1">Start Call</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  <?php endforeach; ?>
  </div>
</main><?php include __DIR__.'/../partials/footer.php'; ?></body></html>
