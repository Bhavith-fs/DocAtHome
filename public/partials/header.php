<?php
require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/functions.php';

session_start();
$user = current_user();
?>
<nav class="bg-white shadow p-4 flex justify-between">
    <div>
        <a href="/index.php" class="font-bold text-lg">DocAtHome</a>
    </div>
    <div>
        <?php if($user): ?>
            <span class="mr-4">Welcome, <?= e($user['name']) ?></span>
            <a href="/auth/logout.php" class="text-red-600 hover:underline">Logout</a>
        <?php else: ?>
            <a href="/auth/login.php" class="mr-4 hover:underline">Login</a>
            <a href="/auth/register.php" class="hover:underline">Register</a>
        <?php endif; ?>
    </div>
</nav>
