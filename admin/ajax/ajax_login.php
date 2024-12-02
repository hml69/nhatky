<?php
session_start();
require $_SERVER['DOCUMENT_ROOT'] . '/serverconnect.php';

$response = [
    'success' => false,
    'errors' => [
        'username' => '',
        'password' => '',
        'general' => ''
    ]
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim(mysqli_real_escape_string($db, $_POST['username']));
    $password = trim(mysqli_real_escape_string($db, $_POST['password']));

    // Kiểm tra trống
    if (empty($username)) {
        $response['errors']['username'] = 'Vui lòng nhập tên đăng nhập!';
    }
    if (empty($password)) {
        $response['errors']['password'] = 'Vui lòng nhập mật khẩu!';
    }

    if (empty($response['errors']['username']) && empty($response['errors']['password'])) {
        // Kiểm tra tài khoản
        $sql_user = "SELECT * FROM users WHERE username = '$username'";
        $result_user = mysqli_query($db, $sql_user);

        if (mysqli_num_rows($result_user) > 0) {
            $user = mysqli_fetch_assoc($result_user);

            if ($user['password'] === md5($password)) {
                if (isset($user['status']) && $user['status'] == 'active') {
                    // Đăng nhập thành công
                    $_SESSION['username'] = $username;
                    $response['success'] = true;
                } else {
                    $response['errors']['username'] = 'Tài khoản của bạn đã bị khóa hoặc chưa kích hoạt!';
                }
            } else {
                $response['errors']['password'] = 'Sai mật khẩu! Vui lòng thử lại.';
            }
        } else {
            $response['errors']['username'] = 'Tên đăng nhập không tồn tại!';
        }
    }
}

// Trả về JSON
header('Content-Type: application/json');
echo json_encode($response);
exit();
?>
