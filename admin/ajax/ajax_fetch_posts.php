<?php
require $_SERVER['DOCUMENT_ROOT'] . '/serverconnect.php';

$result = mysqli_query($db, "SELECT * FROM timeline ORDER BY id DESC");
while ($row = mysqli_fetch_assoc($result)) {
    echo "<tr>
            <td>{$row['title']}</td>
            <td>{$row['content']}</td>
            <td>{$row['date']}</td>
            <td><img src='{$row['image']}' alt='' style='max-width: 100px;'></td>
            <td>
                <button class='btn-primary edit-btn' data-id='{$row['id']}'>Chỉnh sửa</button>
                <button class='btn-danger delete-btn' data-id='{$row['id']}'>Xoá</button>
            </td>
          </tr>";
}
