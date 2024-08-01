<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Comment;
use inc\models\Like;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'delete_comment':
                deleteCommentAjax();
                break;
            case 'like_post':
                handleLikePostAjax();
                break;
            case 'store_redirection_session':
                store_redirection_session();
                break;
            case 'save_comment':
                saveCommentAjax();
                break;
            default:
                echo json_encode(['success' => false]);
                break;
        }
    } else {
        echo json_encode(['success' => false]);
    }
}

function store_redirection_session(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (isset($_POST['redirect_url'])) {
        $_SESSION['redirect_url'] = $_POST['redirect_url'];
        echo json_encode(['status' => 'success']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No URL provided']);
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

function handleLikePostAjax(): void
{
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    $user = Common::getFrontendUser();
    if (!isset($user)) {
        echo json_encode(['status' => 'redirect']);
        exit;
    }

    $user_id = $user['id'];
    $post_id = $_POST['post_id'] ?? null;

    if (!$post_id) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid post ID']);
        exit;
    }

    $likeModel = new Like();

    try {
        if ($likeModel->checkLike($user_id, $post_id)) {
            $likeModel->removeLike($user_id, $post_id);
            echo json_encode(['status' => 'unliked']);
        } else {
            $likeModel->addLike($user_id, $post_id);
            echo json_encode(['status' => 'liked']);
        }
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    }
}

function checkCurrentUrl($needle): bool
{
    return str_contains($_SERVER['REQUEST_URI'], $needle);
}
