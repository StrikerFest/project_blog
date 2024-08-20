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
        <label for="title-filter">Title:</label>
        <input type="text" id="title-filter" name="title" placeholder="Filter by title" value="<?= htmlspecialchars($_GET['title'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="categories-filter">Categories:</label>
        <input type="text" id="categories-filter" name="categories" placeholder="Filter by categories" value="<?= htmlspecialchars($_GET['categories'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="tags-filter">Tags:</label>
        <input type="text" id="tags-filter" name="tags" placeholder="Filter by tags" value="<?= htmlspecialchars($_GET['tags'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="author-filter">Author:</label>
        <input type="text" id="author-filter" name="author" placeholder="Filter by author" value="<?= htmlspecialchars($_GET['author'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="editor-filter">Editor:</label>
        <input type="text" id="editor-filter" name="editor" placeholder="Filter by editor" value="<?= htmlspecialchars($_GET['editor'] ?? '') ?>">
    </div>
    <div class="filter-item">
        <label for="status-filter">Status:</label>
        <select id="status-filter" name="status">
            <option value="" <?= !isset($_GET['status']) || $_GET['status'] === '' ? 'selected' : '' ?>>All</option>
            <option value="draft" <?= $_GET['status'] === 'draft' ? 'selected' : '' ?>>Draft</option>
            <option value="pending_approval" <?= $_GET['status'] === 'pending_approval' ? 'selected' : '' ?>>Pending Approval</option>
            <option value="approval_retracted" <?= $_GET['status'] === 'approval_retracted' ? 'selected' : '' ?>>Approval Retracted</option>
            <option value="approval_denied" <?= $_GET['status'] === 'approval_denied' ? 'selected' : '' ?>>Approval Denied</option>
            <option value="approved" <?= $_GET['status'] === 'approved' ? 'selected' : '' ?>>Approved</option>
            <option value="published_retracted" <?= $_GET['status'] === 'published_retracted' ? 'selected' : '' ?>>Published Retracted</option>
            <option value="published" <?= $_GET['status'] === 'published' ? 'selected' : '' ?>>Published</option>
        </select>
    </div>
    <div class="filter-item">
        <label for="publish-date-filter">Publish Date:</label>
        <input type="date" id="publish-date-filter" name="publish_date" value="<?= htmlspecialchars($_GET['publish_date'] ?? '') ?>">
    </div>
    <div class="filter-item checkbox-container">
        <input type="checkbox" id="include-deleted" name="include_deleted" <?= isset($_GET['include_deleted']) ? 'checked' : '' ?>>
        <label for="include-deleted">Include Deleted?</label>
    </div>
    <div class="filter-btn">
        <button type="submit">Filter</button>
    </div>
</form>

<div class="listing-container">
    <table id="listing-table" class="listing-table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Categories</th>
            <th>Tags</th>
            <th>Author</th>
            <th>Editor</th>
            <th>Status</th>
            <th>Publish Date</th>
            <th style="display:none;">Updated At</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <?php
            // Retrieve categories and tags for each post
            $post_category_ids = Post::getPostCategories($post['post_id'] ?? null);
            $post_tag_ids = Post::getPostTags($post['post_id'] ?? null);

            // Extract only the 'name' from the category and tag arrays
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
                        echo 'No Categories';
                    }
                    ?>
                </td>

                <td>
                    <?php
                    if (!empty($tag_names)) {
                        echo implode(', ', $tag_names);
                    } else {
                        echo 'No Tags';
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
                        <a href="post/delete?action=recover&id=<?= $post['post_id']; ?>" class="btn btn-recover">Recover</a>
                    <?php else: ?>
                        <a href="post/edit?id=<?= $post['post_id']; ?>" class="btn">Edit</a>
                        <a href="post/delete?action=delete&id=<?= $post['post_id']; ?>" class="btn btn-delete">Delete</a>
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
                const confirmDelete = confirm('Are you sure you want to delete this post?');
                if (confirmDelete) {
                    window.location.href = this.href;
                }
            });
        });

        recoverButtons.forEach(button => {
            button.addEventListener('click', function (e) {
                e.preventDefault();
                const confirmRecover = confirm('Are you sure you want to recover this post?');
                if (confirmRecover) {
                    window.location.href = this.href;
                }
            });
        });
    });
</script>
