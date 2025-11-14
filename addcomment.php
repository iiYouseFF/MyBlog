<?php
/**
 * Comment submission handler
 * Expects POST with post_id and content. Requires the user to be logged in.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/includes/config.php";

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Ensure user is logged in
require_login();

$userId = $_COOKIE['user_id'] ?? null;
$postID = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
$content = trim($_POST['content'] ?? '');

if ($userId && $postID > 0 && $content !== '') {
    $stmt = $pdo_conn->prepare("INSERT INTO comments (post_id, user_id, comment, status) VALUES (?, ?, ?, ?)");
    $stmt->execute([$postID, $userId, $content, 'pending']);
}

// Redirect back to post (keep cat query if provided)
$cat = isset($_GET['cat']) ? urlencode($_GET['cat']) : '';
header("Location: post.php?id={$postID}" . ($cat !== '' ? "&cat={$cat}" : ''));
exit;
