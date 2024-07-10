<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;

$data = $args['posts'];
// Import dữ liệu header của Admin
Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Bờ Lốc'
]);
?>
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
    <?php foreach ($data as $row): ?>
        <tr>
            <td><?php echo htmlspecialchars($row['id']); ?></td>
            <td><?php echo htmlspecialchars($row['title']); ?></td>
            <td><?php echo htmlspecialchars('categories'); ?></td>
            <td><?php echo htmlspecialchars('tags'); ?></td>
            <td><?php echo htmlspecialchars('status'); ?></td>
            <td><a href="post/edit?id=<?= $row['id']; ?>" class="btn">Edit</a>
                <a href="post/delete?id=<?= $row['id']; ?>" class="btn">Delete</a></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

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