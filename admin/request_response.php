<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require_once "../includes/config.php";
require_login();
require_admin();
if (!isset($_GET['response'])) {
    header("Location:requests.php");
} else {
    $resopnse = $_GET['response'];
    $uid = $_GET['uid'];
    $rid = $_GET['rid'];
    if ($resopnse == true) {
        $pdo_conn->query("DELETE FROM admin_requests WHERE id = $rid");
        $pdo_conn->query("UPDATE users SET is_admin = 1 WHERE id = $uid");
    } else {
        $pdo_conn->query('DELETE FROM admin_requests WHERE id = $rid');
    }
    header("Location:requests.php");
}
