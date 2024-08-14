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

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Bờ Lốc'
]);
?>
<div class="listing-container">
    <table id="listing-table" class="listing-table">
        <thead>
        <tr>
            <th>Id</th>
            <th>Title</th>
            <th>Categories</th>
            <th>Tags</th>
            <th>Status</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($posts as $post): ?>
            <?php
                $post_category_ids = Post::getPostCategories($post['post_id'] ?? null);
                $post_tag_ids = Post::getPostTags($post['post_id'] ?? null);
            ?>
            <tr>
                <td><?php echo htmlspecialchars($post['post_id']); ?></td>
                <td><?php echo htmlspecialchars($post['title']); ?></td>

                <td>
                    <?php
                    if (isset($post_category_ids) && count($post_category_ids) > 0) {
                        echo implode(', ', $post_category_ids);
                    } else {
                        echo 'No Categories';
                    }
                    ?>
                </td>

                <td>
                    <?php
                    if (isset($post_tag_ids)) {
                        echo implode(', ', $post_tag_ids);
                    } else {
                        echo 'No Tags';
                    }
                    ?>
                </td>

                <td><?php echo htmlspecialchars($post['status']); ?></td>
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
            "searching": true
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
