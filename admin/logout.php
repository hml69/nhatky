<?php
session_start();
session_destroy(); // Hủy phiên hiện tại
header('Location: login.php'); // Chuyển hướng về trang đăng nhập
exit();
?>
