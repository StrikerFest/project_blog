<?php
use inc\helpers\Common;

/**
 * @var mixed $args
 */
$customCss = $args['customCss'] ?? '';
$customAdminCss = $args['customAdminCss'] ?? '';
$success = $_GET['success'] ?? false;

Common::requireTemplate('admin/layouts/headers.php', [
    'title' => 'Custom CSS',
]);
?>

<div class="container">
    <h1>Edit Custom CSS</h1>

    <?php if ($success): ?>
        <div class="success-message">CSS changes saved successfully!</div>
    <?php endif; ?>

    <form method="POST" action="" id="cssForm">
        <div class="form-group">
            <label for="customCss">Custom CSS (User Side):</label>
            <textarea id="customCss" name="customCss" rows="10" style="width: 100%;"><?= htmlspecialchars($customCss) ?></textarea>
        </div>

        <div class="form-group">
            <label for="customAdminCss">Custom CSS (Admin Side):</label>
            <textarea id="customAdminCss" name="customAdminCss" rows="10" style="width: 100%;"><?= htmlspecialchars($customAdminCss) ?></textarea>
        </div>

        <div class="form-group">
            <button type="submit" class="btn">Save CSS</button>
        </div>
    </form>
</div>

<?php
Common::requireTemplate('admin/layouts/footer.php');
?>
<script>
    document.getElementById('cssForm').addEventListener('submit', function(event) {
        if (!confirm('Are you sure you want to save these changes?')) {
            event.preventDefault();
        }
    });
</script>
