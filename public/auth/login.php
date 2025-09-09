<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/functions.php';
if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];
    $user = find_user_by_email($email);
    if($user && password_verify($password, $user['password_hash'])){
        unset($user['password_hash']);
        $_SESSION['user'] = $user;
        if($user['role'] === 'doctor') header('Location: /docathome/public/doctors/dashboard.php');
        elseif($user['role']==='admin') header('Location: /docathome/public/admin/index.php');
        else header('Location: /docathome/public/patients/dashboard.php');
        exit;
    } else {
        $error = "Invalid credentials";
    }
}
?>
<!doctype html><html><head><script src="https://cdn.tailwindcss.com"></script></head>
<body class="bg-gray-50 min-h-screen"><?php include __DIR__ . '/../partials/header.php'; ?>
<div class="max-w-md mx-auto mt-16 p-6 bg-white rounded shadow">
  <h2 class="text-2xl mb-4">Login</h2>
  <?php if(!empty($_GET['registered'])): ?><div class="text-green-600 mb-2">Registered. You can log in now.</div><?php endif; ?>
  <?php if(!empty($error)): ?><div class="text-red-600 mb-2"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <input name="email" type="email" required placeholder="Email" class="w-full mb-2 p-2 border rounded" />
    <input name="password" type="password" required placeholder="Password" class="w-full mb-2 p-2 border rounded" />
    <button class="w-full btn-primary">Login</button>
  </form>
</div><?php include __DIR__ . '/../partials/footer.php'; ?></body></html>
