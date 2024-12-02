<?php
session_start(); // Bắt đầu phiên

// Kiểm tra nếu chưa đăng nhập, chuyển hướng về trang đăng nhập
if (!isset($_SESSION['username'])) {
    header('Location: login.php');
    exit();
}

require $_SERVER['DOCUMENT_ROOT'] . '/serverconnect.php';
?>
<?php include 'layouts/header.php';?>
<div class="container py-5">
  
<a href="logout.php">Đăng xuất</a>
    <h2>Đăng bài viết</h2>
    <form id="postForm" enctype="multipart/form-data">
        <label for="name">Tiêu đề:</label><br>
        <input type="text" id="name" name="name" required><br><br>

        <label for="textt">Nội dung:</label><br>
        <textarea id="textt" name="textt" rows="9" cols="48" required></textarea><br><br>

        <input type="checkbox" id="tick" name="tick" value="contain_pic" onclick="toggleImageUpload()">
        <label for="tick"> Có chứa ảnh</label><br>
        <input type="file" id="image" name="image" style="display:none"><br><br>

        <button type="submit">Đăng bài</button>
    </form>

    <h2>Danh sách bài viết</h2>
    <div id="log-management">
        <table border="1">
            <thead>
                <tr>
                    <th>Tiêu đề</th>
                    <th>Nội dung</th>
                    <th>Ngày</th>
                    <th>Hình ảnh</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody id="postsTable">
                <!-- Bài viết sẽ được load tại đây -->
            </tbody>
        </table>
    </div>
</div>
<!-- Modal chỉnh sửa bài viết -->
<div id="editModal" style="
    display: none; 
    position: fixed; 
    top: 50%; 
    left: 50%; 
    transform: translate(-50%, -50%);
    background: white; 
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2); 
    padding: 20px; 
    border-radius: 10px; 
    width: 400px; 
    z-index: 1000;">
    <h3>Chỉnh sửa bài viết</h3>
    <form id="editForm" enctype="multipart/form-data">
        <!-- ID bài viết (ẩn) -->
        <input type="hidden" id="editId" name="id">

        <!-- Tiêu đề -->
        <label for="editName">Tiêu đề:</label>
        <input type="text" id="editName" name="name" style="width: 100%; padding: 8px; margin: 5px 0;" required><br>

        <!-- Nội dung -->
        <label for="editTextt">Nội dung:</label>
        <textarea id="editTextt" name="textt" rows="5" style="width: 100%; padding: 8px; margin: 5px 0;" required></textarea><br>

        <!-- Ảnh -->
        <label for="editImage">Hình ảnh (nếu muốn thay đổi):</label>
        <input type="file" id="editImage" name="image"><br><br>

        <!-- Nút hành động -->
        <button type="submit" style="background: #4CAF50; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Lưu</button>
        <button type="button" onclick="closeEditModal()" style="background: #f44336; color: white; padding: 10px 20px; border: none; border-radius: 5px; cursor: pointer;">Đóng</button>
    </form>
</div>

<!-- Overlay nền tối -->
<div id="modalOverlay" style="
    display: none; 
    position: fixed; 
    top: 0; 
    left: 0; 
    width: 100%; 
    height: 100%; 
    background: rgba(0, 0, 0, 0.5); 
    z-index: 999;" onclick="closeEditModal()">
</div>

<script>
    // Hiển thị/Ẩn ô chọn ảnh
    function toggleImageUpload() {
        document.getElementById("image").style.display = document.getElementById("tick").checked ? "block" : "none";
    }

    // AJAX để đăng bài viết
    $('#postForm').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'ajax/ajax_upload.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                response = JSON.parse(response);
                alert(response.error || response.msg);
                if (!response.error) loadPosts();
            },
            error: function () {
                alert("Có lỗi xảy ra!");
            }
        });
    });

    // AJAX để load danh sách bài viết
    function loadPosts() {
        $.ajax({
            url: 'ajax/ajax_fetch_posts.php',
            type: 'GET',
            success: function (response) {
                $('#postsTable').html(response);
            }
        });
    }

    // AJAX để xoá bài viết
    $(document).on('click', '.delete-btn', function () {
        var postId = $(this).data('id');
        if (confirm("Bạn có chắc chắn muốn xoá bài viết này không?")) {
            $.ajax({
                url: 'ajax/ajax_delete.php',
                type: 'POST',
                data: { id: postId },
                success: function (response) {
                    response = JSON.parse(response);
                    alert(response.error || response.msg);
                    if (!response.error) loadPosts();
                },
                error: function () {
                    alert("Có lỗi xảy ra khi xoá bài viết.");
                }
            });
        }
    });
    // Hàm mở modal chỉnh sửa
    function openEditModal(post) {
        $('#editId').val(post.id);
        $('#editName').val(post.title);
        $('#editTextt').val(post.content);
        $('#editModal').show();
    }

    // Hàm đóng modal chỉnh sửa
    function closeEditModal() {
        $('#editModal').hide();
    }

    // AJAX để lấy dữ liệu bài viết khi bấm "Chỉnh Sửa"
    $(document).on('click', '.edit-btn', function () {
        var postId = $(this).data('id');
        $.ajax({
            url: 'ajax/ajax_get_post.php',
            type: 'GET',
            data: { id: postId },
            success: function (response) {
                var post = JSON.parse(response);
                if (!post.error) {
                    openEditModal(post);
                } else {
                    alert(post.error);
                }
            },
            error: function () {
                alert("Có lỗi xảy ra!");
            }
        });
    });

    // AJAX để lưu bài viết sau khi chỉnh sửa
    $('#editForm').on('submit', function (e) {
        e.preventDefault();
        var formData = new FormData(this);
        $.ajax({
            url: 'ajax/ajax_edit.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                response = JSON.parse(response);
                alert(response.error || response.msg);
                if (!response.error) {
                    closeEditModal();
                    loadPosts(); // Reload danh sách bài viết
                }
            },
            error: function () {
                alert("Có lỗi xảy ra khi lưu chỉnh sửa!");
            }
        });
    });

    // Load bài viết khi tải trang
    loadPosts();
</script>
    <?php include 'layouts/footer.php';?>