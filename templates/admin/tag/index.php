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

<div class="filter-container">
    <form method="GET" action="" class="filter-container">
        <div class="filter-item">
            <label for="id-filter">Id:</label>
            <input type="text" id="id-filter" name="id" class="short-input" placeholder="ID" value="<?= htmlspecialchars($_GET['id'] ?? '') ?>">
        </div>
        <div class="filter-item">
            <label for="name-filter">Name:</label>
            <input type="text" id="name-filter" name="name" placeholder="Filter by name" value="<?= htmlspecialchars($_GET['name'] ?? '') ?>">
        </div>
        <div class="filter-item">
            <label for="status-filter">Status:</label>
            <select id="status-filter" name="status">
                <option value="" <?= !isset($_GET['status']) || $_GET['status'] === '' ? 'selected' : '' ?>>All</option>
                <option value="enabled" <?= $_GET['status'] === 'enabled' ? 'selected' : '' ?>>Enabled</option>
                <option value="disabled" <?= $_GET['status'] === 'disabled' ? 'selected' : '' ?>>Disabled</option>
            </select>
        </div>
        <div class="filter-item">
            <label for="position-filter">Position:</label>
            <input type="text" id="position-filter" name="position" placeholder="Filter by position" value="<?= htmlspecialchars($_GET['position'] ?? '') ?>">
        </div>
        <div class="filter-item checkbox-container">
            <input type="checkbox" id="include-deleted" name="include_deleted" <?= isset($_GET['include_deleted']) ? 'checked' : '' ?>>
            <label for="include-deleted">Include Deleted?</label>
        </div>
        <div class="filter-btn">
            <button type="submit">Filter</button>
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
                <th>Name</th>
                <th>Status</th>
                <th>Position</th>
                <th>Action</th>
                <th style="display:none;">Updated At</th> 
            </tr>
            </thead>
            <tbody>
            <?php foreach ($args['tags'] as $tag) : ?>
                <tr class="table-row <?= $tag['deleted_at'] ? 'deleted-row' : ''; ?>">
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
