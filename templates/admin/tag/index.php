<?php

/**
 * @var mixed $args
 */

use inc\helpers\Common;

// Import dữ liệu header của Admin
Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Tag'
]);
?>
<div class="listing-container">
    <h1>Tag</h1>
    <div>
        <table id="tagTable" class="listing-styled-table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>

            <?php foreach ($args['tags'] as $tag) : ?>
                <tr class="table-row">
                    <td class="text-align-center"><?= $tag['tag_id']; ?></td>
                    <td><?= $tag['name']; ?></td>
                    <td class="text-align-center"><?= $tag['status']; ?></td>
                    <td class="text-align-center">
                        <a href="tag/edit?id=<?= $tag['tag_id']; ?>" class="listing-btn_action">Edit</a>
                        <a href="tag/delete?id=<?= $tag['tag_id']; ?>" class="listing-btn_action">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Initialize DataTables -->
<script>
    $(document).ready(function() {
        $('#tagTable').DataTable();
    });
</script>
