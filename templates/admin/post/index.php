<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;
use inc\helpers\Post;
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
                    // Check if 'categories' key exists in the post data
                    if (isset($post_category_ids) && count($post_category_ids) > 0) {
                        echo implode(', ',$post_category_ids);
                    } else {
                        echo 'No Categories'; // Display a message if no categories exist
                    }
                    ?>
                </td>

                <td>
                    <?php
                    if (isset($post_tag_ids)) {
                        echo implode(',',$post_tag_ids);
                    } else {
                        echo 'No Tags'; 
                    }
                    ?>
                </td>

                <td><?php echo htmlspecialchars($post['status']); ?></td>
                <td><a href="post/edit?id=<?= $post['post_id']; ?>" class="btn">Edit</a>
                    <a href="post/delete?id=<?= $post['post_id']; ?>" class="btn">Delete</a></td>
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
</script>