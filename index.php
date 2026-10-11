<?php
session_start();
header('Location: ' . (isset($_SESSION['user']) ? 'post.php' : 'login.php'));
exit;
?>