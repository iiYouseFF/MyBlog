<?php
require_once "../includes/config.php";
require_login();
require_admin();

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo_conn->prepare("UPDATE comments SET status = 'rejected' WHERE id = ?");
    $stmt->execute([$id]);
}

header("Location: comments.php");
exit;
