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
                <th>Position</th>
                <th>Action</th>
                <th style="display:none;">Updated At</th> <!-- Hidden Updated At column -->
            </tr>
            </thead>
            <tbody>
            <?php foreach ($args['tags'] as $tag) : ?>
                <tr class="table-row">
                    <td class="text-align-center"><?= $tag['tag_id']; ?></td>
                    <td><?= $tag['name']; ?></td>
                    <td class="text-align-center"><?= $tag['status']; ?></td>
                    <td class="text-align-center"><?= $tag['position']; ?></td>
                    <td class="text-align-center">
                        <?php if ($tag['deleted_at']) : ?>
                            <a href="tag/delete?action=recover&id=<?= $tag['tag_id']; ?>" class="listing-btn_action">Recover</a>
                        <?php else : ?>
                            <a href="tag/edit?id=<?= $tag['tag_id']; ?>" class="listing-btn_action">Edit</a>
                            <a href="tag/delete?action=delete&id=<?= $tag['tag_id']; ?>" class="listing-btn_action">Delete</a>
                        <?php endif; ?>
                    </td>
                    <td style="display:none;"><?= $tag['updated_at']; ?></td> <!-- Hidden Updated At column data -->
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tagTable').DataTable({
            "order": [[5, "desc"]], // Sort by the 6th column (updated_at) in descending order
            "columnDefs": [
                { "targets": 5, "visible": false } // Hide the updated_at column
            ]
        });
    });
</script>
