<?php
require_once __DIR__ . '/../../config/config.php';
session_destroy();
header('Location: /docathome/public/index.php');
exit;
