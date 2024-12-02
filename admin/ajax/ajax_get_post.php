<?php
require $_SERVER['DOCUMENT_ROOT'] . '/serverconnect.php';

$response = ['error' => '', 'id' => '', 'title' => '', 'content' => '', 'image' => ''];

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $result = mysqli_query($db, "SELECT * FROM timeline WHERE id = $id");
    if ($row = mysqli_fetch_assoc($result)) {
        $response = [
            'id' => $row['id'],
            'title' => $row['title'],
            'content' => $row['content'],
            'image' => $row['image']
        ];
    } else {
        $response['error'] = "Không tìm thấy bài viết!";
    }
} else {
    $response['error'] = "ID không hợp lệ!";
}

echo json_encode($response);
