<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Comment
    if (str_contains($_SERVER['REQUEST_URI'], '/post/comment')) {
        $comment = trim($_POST['comment']);

        // TODO: Validate
        if ($comment !== '') {

            // TODO: Save

            echo json_encode([
                'success' => true,
                'comment' => htmlspecialchars($comment)
            ]);
        } else {
            echo json_encode(['success' => false]);
        }
    } else {
        echo json_encode(['success' => false]);
    }
}