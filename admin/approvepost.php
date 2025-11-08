<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../includes/config.php";
require_login();
require_admin();

if (!isset($_GET['id'])) {
    header("Location: posts.php");
    exit;
}

$postId = intval($_GET['id']);

// Update status to approved
$stmt = $pdo_conn->prepare("UPDATE posts SET status = 'published' WHERE id = ?");
$stmt->execute([$postId]);

header("Location: posts.php");
exit;
