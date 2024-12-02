<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Bạn cần đăng nhập!']);
    exit();
}
require $_SERVER['DOCUMENT_ROOT'] . '/serverconnect.php';

$response = ['error' => '', 'msg' => ''];

if (isset($_POST['id'])) {
    $id = intval($_POST['id']);
    $sql = "DELETE FROM timeline WHERE id = $id";
    if (mysqli_query($db, $sql)) {
        $response['msg'] = "Xoá bài viết thành công!";
    } else {
        $response['error'] = "Xoá bài viết thất bại!";
    }
}
echo json_encode($response);
