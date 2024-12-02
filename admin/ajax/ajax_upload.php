<?php
session_start();
if (!isset($_SESSION['username'])) {
    echo json_encode(['error' => 'Bạn cần đăng nhập!']);
    exit();
}
require $_SERVER['DOCUMENT_ROOT'] . '/serverconnect.php';

$response = ['error' => '', 'msg' => ''];

if (isset($_POST['name'], $_POST['textt'])) {
    $tieude = $_POST['name'];
    $noidung = $_POST['textt'];
    $today = date("Y-m-d");

    // UPDATE 02/12/2024 - Mã hoá tên file bằng md5 - By Phạm Gia Huy
    $image = '';
    if (isset($_POST['tick']) && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $originalName = pathinfo($_FILES['image']['name'], PATHINFO_FILENAME);
        $extension = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $hashedName = md5($originalName . time()) . '.' . $extension;
        $image = "/images/" . $hashedName;
        $target = $_SERVER['DOCUMENT_ROOT'] . $image;

        if (!move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $response['error'] = "Tải lên ảnh thất bại.";
            echo json_encode($response);
            exit();
        }
    }

    $sql = "INSERT INTO timeline (title, content, date, image) VALUES ('$tieude', '$noidung', '$today', '$image')";
    if (mysqli_query($db, $sql)) {
        $response['msg'] = "Bài viết đã được đăng thành công!";
    } else {
        $response['error'] = "Đăng bài thất bại!";
    }
}
echo json_encode($response);
?>
