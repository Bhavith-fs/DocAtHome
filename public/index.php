<?php
session_start();
require_once __DIR__ . "/../config/config.php";

// Handle doctor search
$search = $_GET['search'] ?? '';
$sql = "SELECT id, name, specialization, profile_image, bio 
        FROM users 
        WHERE role='doctor' AND is_verified=1";
$params = [];
if ($search) {
    $sql .= " AND (name LIKE ? OR specialization LIKE ?)";
    $params = ["%$search%", "%$search%"];
}
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$doctors = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>DocAtHome</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

<!-- 🔹 Navbar -->
<nav class="bg-blue-600 p-4 text-white flex justify-between items-center">
  <h1 class="text-2xl font-bold">DocAtHome</h1>
  <div>
    <?php if(isset($_SESSION['user_id'])): ?>
      <span class="mr-4">Hello, <?= htmlspecialchars($_SESSION['role']) ?></span>
      <a href="auth/logout.php" class="bg-red-500 px-3 py-1 rounded">Logout</a>
    <?php else: ?>
      <a href="auth/login.php" class="bg-yellow-400 px-3 py-1 rounded">Login</a>
      <a href="auth/register.php" class="ml-2 bg-green-500 px-3 py-1 rounded">Register</a>
    <?php endif; ?>
  </div>
</nav>

<!-- 🔹 Hero Section (kept from your HTML) -->
<header class="bg-gradient-to-r from-blue-600 to-blue-800 text-white text-center py-20">
  <h2 class="text-4xl font-bold">Book & Talk with Doctors Online</h2>
  <p class="mt-2 text-lg">Healthcare at your fingertips</p>
  <form method="get" class="mt-6 flex justify-center">
    <input type="text" name="search" placeholder="Search doctors by name or specialty"
           value="<?= htmlspecialchars($search) ?>"
           class="px-4 py-2 rounded-l-lg text-black w-1/3">
    <button type="submit" class="bg-yellow-500 px-4 py-2 rounded-r-lg">Search</button>
  </form>
</header>

<!-- 🔹 Features / Services (kept exactly as in your HTML) -->
<section class="py-16 bg-gray-100">
  <div class="max-w-6xl mx-auto px-6 text-center">
    <h2 class="text-3xl font-bold text-blue-700 mb-6">Our Features</h2>
    <p class="text-gray-600 mb-12">We bring healthcare closer to you with these services:</p>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
        <h3 class="font-bold text-lg mb-2">Book Appointments</h3>
        <p class="text-gray-600">Easily book appointments with trusted doctors.</p>
      </div>
      <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
        <h3 class="font-bold text-lg mb-2">Video Consultation</h3>
        <p class="text-gray-600">Talk to doctors from the comfort of your home.</p>
      </div>
      <div class="bg-white p-6 rounded shadow hover:shadow-lg transition">
        <h3 class="font-bold text-lg mb-2">Secure Chat</h3>
        <p class="text-gray-600">Chat with doctors privately and securely.</p>
      </div>
    </div>
  </div>
</section>

<!-- 🔹 Doctors Section (dynamic from DB) -->
<section class="py-16">
  <div class="max-w-6xl mx-auto px-6">
    <h2 class="text-3xl font-bold text-center mb-8">Available Doctors</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-8">
      <?php if ($doctors): ?>
        <?php foreach($doctors as $doc): ?>
          <div class="bg-white shadow-lg rounded-lg p-6 text-center hover:shadow-2xl transition">
            <img src="<?= $doc['profile_image'] ?: 'https://via.placeholder.com/150' ?>" 
                 alt="Doctor photo" class="w-24 h-24 rounded-full mx-auto mb-4 border-2 border-blue-600">
            <h3 class="text-xl font-bold"><?= htmlspecialchars($doc['name']) ?></h3>
            <p class="text-blue-600"><?= htmlspecialchars($doc['specialization']) ?></p>
            <p class="text-gray-600 mt-2 text-sm"><?= htmlspecialchars(substr($doc['bio'],0,70)) ?>...</p>
            <a href="patients/book.php?doctor_id=<?= $doc['id'] ?>" 
               class="mt-4 inline-block bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
               Book Now
            </a>
          </div>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="col-span-3 text-center text-gray-500">No doctors found.</p>
      <?php endif; ?>
    </div>
  </div>
</section>

<!-- 🔹 Testimonials Section (kept from your HTML) -->
<section class="py-16 bg-gray-100">
  <div class="max-w-6xl mx-auto px-6 text-center">
    <h2 class="text-3xl font-bold text-blue-700 mb-6">What Our Patients Say</h2>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
      <div class="bg-white p-6 rounded shadow">
        <p class="text-gray-600 mb-4">"Great service! Booking was simple and I talked to my doctor in minutes."</p>
        <h4 class="font-bold">– John Doe</h4>
      </div>
      <div class="bg-white p-6 rounded shadow">
        <p class="text-gray-600 mb-4">"The video consultation worked smoothly, highly recommended!"</p>
        <h4 class="font-bold">– Sarah K.</h4>
      </div>
      <div class="bg-white p-6 rounded shadow">
        <p class="text-gray-600 mb-4">"Safe, fast and reliable. I use it for my family too."</p>
        <h4 class="font-bold">– Dr. Emily</h4>
      </div>
    </div>
  </div>
</section>

<!-- 🔹 Footer (kept from your HTML) -->
<footer class="bg-blue-700 text-white text-center py-6">
  <p>© <?= date("Y") ?> DocAtHome. All rights reserved.</p>
</footer>

</body>
</html>
