<?php
session_start();

// Example: check login
if (!isset($_SESSION['user_id'])) {
    http_response_code(403);
    exit;
}

$filename = basename($_GET['file']); // prevent path traversal
$path = "../assets/" . $filename;

if (!file_exists($path)) {
    http_response_code(404);
    exit;
}

header("Content-Type: image/png");
header("Cache-Control: private, no-store");
readfile($path);
exit;
?>