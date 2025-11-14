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

// Optionally: fetch the post to delete its image file
$stmt = $pdo_conn->prepare("SELECT image FROM posts WHERE id = ?");
$stmt->execute([$postId]);
$post = $stmt->fetch(PDO::FETCH_ASSOC);

if ($post) {
    // Delete image from server
    if (!empty($post['image']) && file_exists($post['image'])) {
        unlink($post['image']);
    }

    // Delete post from database
    $stmt = $pdo_conn->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$postId]);
}

header("Location: posts.php");
exit;
