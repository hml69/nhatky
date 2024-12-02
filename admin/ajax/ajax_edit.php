<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Bạn cần đăng nhập!']);
    exit();
}
require $_SERVER['DOCUMENT_ROOT'] . '/serverconnect.php';

$response = ['error' => '', 'msg' => ''];

if (isset($_POST['id'], $_POST['name'], $_POST['textt'])) {
    $id = intval($_POST['id']);
    $tieude = $_POST['name'];
    $noidung = $_POST['textt'];

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image = "/images/" . basename($_FILES['image']['name']);
        $target = $_SERVER['DOCUMENT_ROOT'] . $image;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $response['error'] = "Tải lên ảnh thất bại.";
            echo json_encode($response);
            exit();
        }
        $sql = "UPDATE timeline SET title = '$tieude', content = '$noidung', image = '$image' WHERE id = $id";
    } else {
        $sql = "UPDATE timeline SET title = '$tieude', content = '$noidung' WHERE id = $id";
    }

    if (mysqli_query($db, $sql)) {
        $response['msg'] = "Cập nhật bài viết thành công!";
    } else {
        $response['error'] = "Cập nhật bài viết thất bại!";
    }
} else {
    $response['error'] = "Dữ liệu không hợp lệ!";
}

echo json_encode($response);
