<?php
require_once __DIR__.'/../../config/config.php';
require_once __DIR__.'/../../config/functions.php';
$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? AND role='doctor'");
$stmt->execute([$id]);
$doc = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$doc) die('Doctor not found');
?>
<!doctype html><html><head><script src="https://cdn.tailwindcss.com"></script></head><body>
<?php include __DIR__.'/../partials/header.php'; ?>
<main class="max-w-4xl mx-auto p-6">
  <div class="bg-white rounded shadow p-6">
    <div class="flex gap-6">
      <img src="<?= e($doc['profile_image'] ?: '/docathome/public/uploads/default_doc.png') ?>" class="w-32 h-32 rounded" />
      <div>
        <h1 class="text-2xl font-bold"><?= e($doc['name']) ?></h1>
        <div class="text-gray-600"><?= e($doc['specialization']) ?></div>
        <p class="mt-3"><?= nl2br(e($doc['bio'])) ?></p>
        <div class="mt-4">
          <a class="btn-primary" href="/docathome/public/patients/book.php?doctor_id=<?= $doc['id'] ?>">Book Appointment</a>
          <a class="ml-2" href="/docathome/public/patients/chat.php?doctor=<?= $doc['id'] ?>">Chat</a>
        </div>
      </div>
    </div>
  </div>
</main>
<?php include __DIR__.'/../partials/footer.php'; ?></body></html>
