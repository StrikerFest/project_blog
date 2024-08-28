<?php

/**
 * @var mixed $args
 */

use inc\helpers\admin\Post;
use inc\helpers\Common;
use inc\models\Category;
use inc\models\Tag;

$posts = $args['posts'];
$categories = Category::getCategories();
$tags = Tag::getTags();
$statuses = [
    'draft',
    'pending_approval',
    'approval_retracted',
    'approval_denied',
    'approved',
    'published_retracted',
    'published'
];

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Bờ Lốc'
]);
?>
<form method="GET" action="" class="filter-container">
    <div class="filter-item">
        <label for="id-filter">Id:</label>
        <input type="text" id="id-filter" name="id" class="short-input" placeholder="ID" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="title-filter">Tiêu đề:</label>
        <input type="text" id="title-filter" name="title" placeholder="Lọc theo tiêu đề" value="<?= htmlspecialchars($_GET['title'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="categories-filter">Danh mục:</label>
        <input type="text" id="categories-filter" name="categories" placeholder="Lọc theo danh mục" value="<?= htmlspecialchars($_GET['categories'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="tags-filter">Thẻ:</label>
        <input type="text" id="tags-filter" name="tags" placeholder="Lọc theo thẻ" value="<?= htmlspecialchars($_GET['tags'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="author-filter">Tác giả:</label>
        <input type="text" id="author-filter" name="author" placeholder="Lọc theo tác giả" value="<?= htmlspecialchars($_GET['author'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="editor-filter">Biên tập viên:</label>
        <input type="text" id="editor-filter" name="editor" placeholder="Lọc theo biên tập viên" value="<?= htmlspecialchars($_GET['editor'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="status-filter">Trạng thái:</label>
        <select id="status-filter" name="status">
            <option value="" <?= !isset($_GET['status']) || $_GET['status'] === '' ? 'selected' : '' ?>>Tất cả</option>
            <option value="draft" <?= $_GET['status'] === 'draft' ? 'selected' : '' ?>>Nháp</option>
            <option value="pending_approval" <?= $_GET['status'] === 'pending_approval' ? 'selected' : '' ?>>Chờ phê duyệt</option>
            <option value="approval_retracted" <?= $_GET['status'] === 'approval_retracted' ? 'selected' : '' ?>>Rút lại phê duyệt</option>
            <option value="approval_denied" <?= $_GET['status'] === 'approval_denied' ? 'selected' : '' ?>>Từ chối phê duyệt</option>
            <option value="approved" <?= $_GET['status'] === 'approved' ? 'selected' : '' ?>>Đã phê duyệt</option>
            <option value="published_retracted" <?= $_GET['status'] === 'published_retracted' ? 'selected' : '' ?>>Rút lại xuất bản</option>
            <option value="published" <?= $_GET['status'] === 'published' ? 'selected' : '' ?>>Đã xuất bản</option>
        </select>
    </div>
    <div class="filter-item">
        <label for="publish-date-filter">Ngày xuất bản:</label>
        <input type="date" id="publish-date-filter" name="publish_date" value="<?= htmlspecialchars($_GET['publish_date'] ?? '') ?>">
    </div>
    <div class="filter-item checkbox-container">
        <input type="checkbox" id="include-deleted" name="include_deleted" <?= isset($_GET['include_deleted']) ? 'checked' : '' ?>>
        <label for="include-deleted">Bao gồm các mục đã xóa?</label>
    </div>
    <div class="filter-btn">
        <button type="submit">Lọc</button>
    </div>
</form>

<div class="listing-container">
    <table id="listing-table" class="listing-table">
        <thead>
        <tr>
            <th>ID</th>
            <th>Tiêu đề</th>
            <th>Danh mục</th>
            <th>Thẻ</th>
            <th>Tác giả</th>
            <th>Biên tập viên</th>
            <th>Trạng thái</th>
            <th>Ngày xuất bản</th>
            <th style="display:none;">Cập nhật lúc</th>
            <th>Hành động</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <?php
            // Lấy danh mục và thẻ cho mỗi bài viết
            $post_category_ids = Post::getPostCategories($post['post_id'] ?? null);
            $post_tag_ids = Post::getPostTags($post['post_id'] ?? null);

            // Chỉ lấy tên từ các mảng danh mục và thẻ
            $category_names = array_column($post_category_ids, 'name');
            $tag_names = array_column($post_tag_ids, 'name');
            ?>
            <tr>
                <td><?php echo htmlspecialchars($post['post_id']); ?></td>
                <td><?php echo htmlspecialchars($post['title']); ?></td>

                <td>
                    <?php
                    if (!empty($category_names)) {
                        echo implode(', ', $category_names);
                    } else {
                        echo 'Không có danh mục';
                    }
                    ?>
                </td>

                <td>
                    <?php
                    if (!empty($tag_names)) {
                        echo implode(', ', $tag_names);
                    } else {
                        echo 'Không có thẻ';
                    }
                    ?>
                </td>

                <td><?php echo htmlspecialchars($post['author_name']); ?></td>
                <td><?php echo htmlspecialchars($post['editor_name'] ?? 'N/A'); ?></td>
                <td><?php echo htmlspecialchars($post['status']); ?></td>
                <td><?php echo htmlspecialchars($post['published_at'] ?? 'N/A'); ?></td>
                <td style="display:none;"><?php echo htmlspecialchars($post['updated_at']); ?></td>
                <td>
                    <?php if ($post['deleted_at']): ?>
                        <a href="post/delete?action=recover&id=<?= $post['post_id']; ?>" class="btn btn-recover">Khôi phục</a>
                    <?php else: ?>
                        <a href="post/edit?id=<?= $post['post_id']; ?>" class="btn">Chỉnh sửa</a>
                        <a href="post/delete?action=delete&id=<?= $post['post_id']; ?>" class="btn btn-delete">Xóa</a>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
Common::requireTemplate('admin/layouts/footer.php');
?>
<script>
    $(document).ready(function() {
        $('#listing-table').DataTable({
            "searching": true,
            "order": [[8, "desc"]],
            "columnDefs": [
                { "targets": [8], "visible": false }
            ]
        });
    });

    document.addEventListener('DOMContentLoaded', function () {
        const deleteButtons = document.querySelectorAll('.btn-delete');
        const recoverButtons = document.querySelectorAll('.btn-recover');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const confirmDelete = confirm('Bạn có chắc chắn muốn xóa bài viết này không?');
                if (confirmDelete) {
                    window.location.href = this.href;
                }
            });
        });

        recoverButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const confirmRecover = confirm('Bạn có chắc chắn muốn khôi phục bài viết này không?');
                if (confirmRecover) {
                    window.location.href = this.href;
                }
            });
        });
    });
</script>
