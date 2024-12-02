<!-- UPDATE 02/12/2024 - Trang login cho admin - By Phạm Gia Huy -->
<?php
session_start(); // Bắt đầu phiên

// Nếu người dùng đã đăng nhập, chuyển hướng về trang thêm bài viết
if (isset($_SESSION['username'])) {
    header('Location: index.php');
    exit();
}
require $_SERVER['DOCUMENT_ROOT'] . '/serverconnect.php';
?>
<?php include 'layouts/header.php'; ?>

<div class="container py-5">
    <h2 class="text-center mb-4">EVERYDAY ADMIN</h2>
    <form id="loginForm" class="mx-auto" style="max-width: 400px;">
        <!-- Tên đăng nhập -->
        <div class="form-group">
            <label for="username">Tên đăng nhập</label>
            <input 
                type="text" 
                name="username" 
                id="username" 
                class="form-control" 
                placeholder="Nhập tên đăng nhập"
            >
            <div class="invalid-feedback" id="error-username"></div>
        </div>

        <!-- Mật khẩu -->
        <div class="form-group">
            <label for="password">Mật khẩu</label>
            <input 
                type="password" 
                name="password" 
                id="password" 
                class="form-control" 
                placeholder="Nhập mật khẩu"
            >
            <div class="invalid-feedback" id="error-password"></div>
        </div>

        <!-- Nút đăng nhập -->
        <button 
            type="button" 
            id="loginButton" 
            class="btn btn-primary btn-block mt-3"
        >
            Đăng nhập
        </button>
        <div 
            class="alert alert-danger mt-3 d-none" 
            id="error-general"
        ></div>
    </form>
</div>

<?php include 'layouts/footer.php'; ?>

<script>
    $(document).ready(function () {
        // Xử lý sự kiện khi nhấn nút Đăng nhập
        $('#loginButton').on('click', function () {
            // Reset trạng thái lỗi
            $('.invalid-feedback').text('');
            $('.form-control').removeClass('is-invalid');
            $('#error-general').addClass('d-none').text('');

            // Lấy dữ liệu từ form
            const username = $('#username').val().trim();
            const password = $('#password').val().trim();
            const $loginButton = $(this);

            // Hiển thị trạng thái loading trên nút
            $loginButton.prop('disabled', true).html(`
                <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
            `);

            // Gửi AJAX
            $.ajax({
                url: 'ajax/ajax_login.php',
                type: 'POST',
                data: { username, password },
                success: function (response) {
                    if (response.success) {
                        // Đăng nhập thành công, chuyển hướng
                        window.location.href = 'index.php';
                    } else {
                        // Hiển thị lỗi từ server
                        if (response.errors?.username) {
                            $('#username').addClass('is-invalid');
                            $('#error-username').text(response.errors.username);
                        }
                        if (response.errors?.password) {
                            $('#password').addClass('is-invalid');
                            $('#error-password').text(response.errors.password);
                        }
                        if (response.errors?.general) {
                            $('#error-general').removeClass('d-none').text(response.errors.general);
                        }
                    }
                },
                error: function () {
                    // Lỗi khi kết nối đến server
                    $('#error-general').removeClass('d-none').text('Đã xảy ra lỗi! Vui lòng thử lại.');
                },
                complete: function () {
                    // Khôi phục nút về trạng thái ban đầu
                    $loginButton.prop('disabled', false).html('Đăng nhập');
                }
            });
        });
    });
</script>
