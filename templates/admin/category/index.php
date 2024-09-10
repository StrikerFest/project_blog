<?php
/**
 * @var mixed $args
 */

use inc\helpers\Common;

$userRole = Common::getCurrentBackendUserRole();

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Danh mục'
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
            <label for="slug-filter">Slug:</label>
            <input type="text" id="slug-filter" name="slug" placeholder="Lọc theo slug" value="<?= htmlspecialchars($_GET['slug'] ?? '') ?>">
        </div>
        <div class="filter-item">
            <label for="status-filter">Trạng thái:</label>
            <select id="status-filter" name="status">
                <option value="" <?= !isset($_GET['status']) || $_GET['status'] === '' ? 'selected' : '' ?>>Tất cả</option>
                <option value="enabled" <?= $_GET['status'] === 'enabled' ? 'selected' : '' ?>>Kích hoạt</option>
                <option value="disabled" <?= $_GET['status'] === 'disabled' ? 'selected' : '' ?>>Không kích hoạt</option>
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
    <h1>Danh mục</h1>
    <div>
        <table id="categoryTable" class="listing-styled-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Tên</th>
                <th>Slug</th>
                <th>Mô tả</th>
                <th>Trạng thái</th>
                <th>Vị trí</th>
                <th>Hành động</th>
                <th style="display:none;">Cập nhật lúc</th> <!-- Hidden Updated At column -->
            </tr>
            </thead>
            <tbody>
            <?php foreach ($args['categories'] as $category) : ?>
                <tr class="table-row <?= $category['deleted_at'] ? 'deleted-row' : ''; ?>">
                    <td class="text-align-center"><?= $category['category_id']; ?></td>
                    <td><?= $category['name']; ?></td>
                    <td class="text-align-center"><?= $category['slug']; ?></td>
                    <td class="text-align-center"><?= $category['description']; ?></td>
                    <td class="text-align-center"><?= $category['status']; ?></td>
                    <td class="text-align-center"><?= $category['position']; ?></td>
                    <?php if ($userRole === 'author'): ?>
                        <td class="text-align-center">
                            <a href="category/edit?id=<?= $category['category_id']; ?>" class="listing-btn_action">Chỉnh sửa</a>
                        </td>
                    <?php else: ?>
                        <td class="text-align-center">
                            <?php if ($category['deleted_at']) : ?>
                                <a href="category/delete?action=recover&id=<?= $category['category_id']; ?>" class="listing-btn_action">Khôi phục</a>
                            <?php else : ?>
                                <a href="category/edit?id=<?= $category['category_id']; ?>" class="listing-btn_action">Chỉnh sửa</a>
                                <a href="category/delete?action=delete&id=<?= $category['category_id']; ?>" class="listing-btn_action">Xóa</a>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>
                    <td style="display:none;"><?= $category['updated_at']; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#categoryTable').DataTable({
            "order": [[7, "desc"]], // Sort by the 8th column (updated_at) in descending order
            "columnDefs": [
                {"targets": 7, "visible": false} // Hide the updated_at column
            ]
        });
    });
</script>
