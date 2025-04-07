<?php
require_once 'includes/auth.php';

session_start();
$_SESSION = array();
session_destroy();

header("Location: login.html");
exit();
?>