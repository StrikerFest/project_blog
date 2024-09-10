<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;

$userRole = Common::getCurrentBackendUserRole();

// Nhúng dữ liệu header của Admin
Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Tag'
]);
?>

<div class="filter-container">
    <form method="GET" action="" class="filter-container">
        <div class="filter-item">
            <label for="id-filter">Id:</label>
            <input type="text" id="id-filter" name="id" class="short-input" placeholder="ID" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
        </div>
        <div class="filter-item">
            <label for="name-filter">Tên:</label>
            <input type="text" id="name-filter" name="name" placeholder="Lọc theo tên" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
        </div>
        <div class="filter-item">
            <label for="status-filter">Trạng thái:</label>
            <select id="status-filter" name="status">
                <option value="" <?= !isset($_GET['status']) || $_GET['status'] === '' ? 'selected' : '' ?>>Tất cả</option>
                <option value="enabled" <?= $_GET['status'] === 'enabled' ? 'selected' : '' ?>>Kích hoạt</option>
                <option value="disabled" <?= $_GET['status'] === 'disabled' ? 'selected' : '' ?>>Tắt</option>
            </select>
        </div>
        <div class="filter-item">
            <label for="position-filter">Vị trí:</label>
            <input type="text" id="position-filter" name="position" placeholder="Lọc theo vị trí" value="<?= htmlspecialchars($_GET['position'] ?? '') ?>">
        </div>
        <div class="filter-item checkbox-container">
            <input type="checkbox" id="include-deleted" name="include_deleted" <?= isset($_GET['include_deleted']) ? 'checked' : '' ?>>
            <label for="include-deleted">Bao gồm đã xóa?</label>
        </div>
        <div class="filter-btn">
            <button type="submit">Lọc</button>
        </div>
    </form>
</div>

<div class="listing-container">
    <h1>Tag</h1>
    <div>
        <table id="tagTable" class="listing-styled-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Tên</th>
                <th>Trạng thái</th>
                <th>Vị trí</th>
                <th>Hành động</th>
                <th style="display:none;">Cập nhật lúc</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($args['tags'] as $tag) : ?>
                <tr class="table-row <?= $tag['deleted_at'] ? 'deleted-row' : ''; ?>">
                    <td class="text-align-center"><?= $tag['tag_id']; ?></td>
                    <td><?= $tag['name']; ?></td>
                    <td class="text-align-center"><?= $tag['status']; ?></td>
                    <td class="text-align-center"><?= $tag['position']; ?></td>
                    <?php if ($userRole === 'author'): ?>
                        <td class="text-align-center">
                            <a href="tag/edit?id=<?= $tag['tag_id']; ?>" class="listing-btn_action">Chỉnh sửa</a>
                        </td>
                    <?php else: ?>
                        <td class="text-align-center">
                            <?php if ($tag['deleted_at']) : ?>
                                <a href="tag/delete?action=recover&id=<?= $tag['tag_id']; ?>" class="listing-btn_action">Khôi phục</a>
                            <?php else : ?>
                                <a href="tag/edit?id=<?= $tag['tag_id']; ?>" class="listing-btn_action">Chỉnh sửa</a>
                                <a href="tag/delete?action=delete&id=<?= $tag['tag_id']; ?>" class="listing-btn_action">Xóa</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td style="display:none;"><?= $tag['updated_at']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#tagTable').DataTable({
            "order": [[5, "desc"]], // Sắp xếp theo cột thứ 6 (cập nhật lúc) theo thứ tự giảm dần
            "columnDefs": [
                {"targets": 5, "visible": false} // Ẩn cột cập nhật lúc
            ]
        });
    });
</script>
