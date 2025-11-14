<?php
/**
 * Delete Category Handler
 * 
 * This script handles the deletion of categories. It checks for admin privileges
 * and uses prepared statements to prevent SQL injection attacks.
 * 
 * @package MyBlog
 * @subpackage Admin
 */

// Include config and require authentication
require_once "../includes/config.php";
require_login();
require_admin();

// Validate category ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: categories.php");
    exit;
}

// Use prepared statement to prevent SQL injection
$category_id = (int)$_GET['id'];
$stmt = $pdo_conn->prepare("DELETE FROM categories WHERE id = ?");
$stmt->execute([$category_id]);

// Redirect back to categories page
header("Location: categories.php");
exit;
