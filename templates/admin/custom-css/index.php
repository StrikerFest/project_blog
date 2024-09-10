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
            <textarea id="customCss" name="customCss"><?= htmlspecialchars($customCss) ?></textarea>
        </div>

        <div class="form-group">
            <label for="customAdminCss">Custom Admin CSS:</label>
            <textarea id="customAdminCss" name="customAdminCss"><?= htmlspecialchars($customAdminCss) ?></textarea>
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
    // Initialize CodeMirror for the customCss field
    var customCssEditor = CodeMirror.fromTextArea(document.getElementById("customCss"), {
        lineNumbers: true,
        mode: "css",
        theme: "default",
        matchBrackets: true,
        extraKeys: {"Ctrl-Space": "autocomplete"}, // Enable autocomplete on Ctrl+Space
        hintOptions: {
            completeSingle: false // Ensure the user has to select suggestions
        }
    });

    // Initialize CodeMirror for the customAdminCss field
    var customAdminCssEditor = CodeMirror.fromTextArea(document.getElementById("customAdminCss"), {
        lineNumbers: true,
        mode: "css",
        theme: "default",
        matchBrackets: true,
        extraKeys: {"Ctrl-Space": "autocomplete"}, // Enable autocomplete on Ctrl+Space
        hintOptions: {
            completeSingle: false // Ensure the user has to select suggestions
        }
    });

    customCssEditor.on("inputRead", function(cm, change) {
        if (!cm.state.completionActive && /* Enforce a minimum number of characters */
            change.text[0] !== " ") { // Don't trigger on whitespace
            cm.showHint({completeSingle: false});
        }
    });

    customAdminCssEditor.on("inputRead", function(cm, change) {
        if (!cm.state.completionActive && change.text[0] !== " ") {
            cm.showHint({completeSingle: false});
        }
    });
</script>
<script>
    document.getElementById('cssForm').addEventListener('submit', function(event) {
        if (!confirm('Are you sure you want to save these changes?')) {
            event.preventDefault();
        }
    });
</script>
