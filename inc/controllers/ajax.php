<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Comment;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'delete_comment') {
        deleteCommentAjax();
    } elseif (checkCurrentUrl('/post/comment')) {
        saveCommentAjax();
    } else {
        echo json_encode(['success' => false]);
    }
}

function saveCommentAjax(): void
{
    $commentContent = trim($_POST['comment']);
    $post_id = intval($_POST['post_id']);
    $user_id = intval($_POST['user_id']);

    if ($commentContent !== '' && $post_id > 0 && $user_id > 0) {
        $comment = new Comment();
        $result = $comment->saveComment($post_id, $user_id, $commentContent);

        if ($result) {
            echo json_encode([
                'success' => true,
                'comment' => htmlspecialchars($commentContent),
                'comment_id' => $result
            ]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to save comment']);
        }
    } else {
        echo json_encode(['success' => false]);
    }
}

function deleteCommentAjax(): void
{
    $comment_id = intval($_POST['comment_id']);
    $post_id = intval($_POST['post_id']);
    $user_id = intval($_POST['user_id']);

    if ($comment_id > 0 && $post_id > 0 && $user_id > 0) {
        $comment = new Comment();
        $result = $comment->deleteComment($comment_id, $post_id, $user_id);

        if ($result) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to delete comment']);
        }
    } else {
        echo json_encode(['success' => false]);
    }
}

function checkCurrentUrl($needle): bool
{
    return str_contains($_SERVER['REQUEST_URI'], $needle);
}
