<?php
/**
 * Global Configuration File
 * 
 * This file handles database connection, session management, and user authentication.
 * It provides global variables and utilities used throughout the application.
 * 
 * @package MyBlog
 * @version 1.0
 */

// NOTE: This project uses cookie-based auth (per user preference).
// Do NOT start or rely on PHP sessions here. Authentication is based on
// the `user_id` and `username` cookies.

// Database configuration
$hostname = "localhost";
$dbname = "blog_db";
$username = "phpmyadmin";
$password = "SrN6.bEp3T[OThOk";

// Establish database connection
try {
    $pdo_conn = new PDO("mysql:host=$hostname;dbname=$dbname", $username, $password);
    // Set PDO to throw exceptions for easier debugging
    $pdo_conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Ensure proper character encoding
    $pdo_conn->exec("SET NAMES 'utf8mb4'");
} catch (PDOException $e) {
    error_log("Database Connection Error: " . $e->getMessage());
    die("Database connection failed. Please try again later.");
}

// Initialize auth-related variables with safe defaults
$currentUser = null;
$currentUserId = null;
$user = null;
$isAdmin = false;

// Authentication check using cookies only
if (isset($_COOKIE['user_id']) && isset($_COOKIE['username'])) {
    $currentUserId = intval($_COOKIE['user_id']);
    $currentUser = $_COOKIE['username'];

    // Verify user exists and get their data
    try {
        $stmt = $pdo_conn->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$currentUserId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Check admin status
            $isAdmin = (!empty($user['is_admin']) && $user['is_admin'] == 1);

            // Optionally update last activity timestamp (best-effort)
            try {
                $u = $pdo_conn->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $u->execute([$currentUserId]);
            } catch (Exception $e) {
                // non-fatal
            }
        } else {
            // Invalid user - clear auth cookies
            setcookie('username', '', time() - 3600, '/');
            setcookie('user_id', '', time() - 3600, '/');
            $user = null;
            $currentUser = null;
            $currentUserId = null;
            $isAdmin = false;
        }
    } catch (PDOException $e) {
        error_log("Auth Check Error: " . $e->getMessage());
    }
}

// Define common utility functions
function sanitize_output($text) {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function is_logged_in() {
    global $user;
    return !empty($user) && is_array($user);
}

function require_login() {
    if (!is_logged_in()) {
        // Redirect relative to admin vs public pages
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($uri, '/admin/') !== false) {
            header("Location: ../login.php");
        } else {
            header("Location: ./login.php");
        }
        exit;
    }
}

function require_admin() {
    global $isAdmin;
    if (!$isAdmin) {
        $uri = $_SERVER['REQUEST_URI'] ?? '';
        if (strpos($uri, '/admin/') !== false) {
            header("Location: ../index.php");
        } else {
            header("Location: ./index.php");
        }
        exit;
    }
}
