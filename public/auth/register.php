<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/functions.php';

session_start();
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];
    $role = in_array($_POST['role'], ['patient','doctor']) ? $_POST['role'] : 'patient';

    if (find_user_by_email($email)) {
        $error = "Email already registered";
    } else {
        try {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (role, name, email, password_hash) VALUES (?,?,?,?)");
            $stmt->execute([$role, $name, $email, $password_hash]);

            // Redirect to login after registration
            header('Location: login.php?registered=1');
            exit;
        } catch (PDOException $e) {
            $error = "Database error: " . $e->getMessage();
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<script src="https://cdn.tailwindcss.com"></script>
<title>Register</title>
</head>
<body class="bg-gray-50 min-h-screen">
<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="max-w-md mx-auto mt-16 p-6 bg-white rounded shadow">
  <h2 class="text-2xl mb-4 font-semibold">Register</h2>

  <?php if(!empty($error)): ?>
    <div class="text-red-600 mb-4"><?= e($error) ?></div>
  <?php endif; ?>

  <form method="post" class="space-y-3">
    <input name="name" required placeholder="Full Name" class="w-full p-2 border rounded" />
    <input name="email" type="email" required placeholder="Email" class="w-full p-2 border rounded" />
    <input name="password" type="password" required placeholder="Password" class="w-full p-2 border rounded" />
    <select name="role" class="w-full p-2 border rounded">
      <option value="patient">I'm a patient</option>
      <option value="doctor">I'm a doctor</option>
    </select>
    <button type="submit" class="w-full bg-blue-600 text-white p-2 rounded hover:bg-blue-700 transition">Register</button>
  </form>
</div>

<?php include __DIR__ . '/../partials/footer.php'; ?>
</body>
</html>
