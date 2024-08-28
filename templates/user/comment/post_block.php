<?php
/**
 * @var $args
 */

use inc\helpers\Common;
use inc\models\Comment;

$post_id = $args['post_id'];
$user = $args['user'];
$user_id = $user['id'] ?? null;

// Lấy các bình luận từ cơ sở dữ liệu
$commentModel = new Comment();
$comments = $commentModel->getCommentsByPostId($post_id);
?>
<div class="comment-section">
    <?php if (!empty($user_id)): ?>
        <form id="comment-form" class="comment-textarea" method="POST">
            <img src="<?php echo $user['profile_picture']; ?>" alt="Ảnh đại diện" class="comment-avatar">
            <label>
                <textarea id="comment-textarea" name="comment" placeholder="Viết bình luận..."></textarea>
            </label>
            <input type="hidden" name="post_id" value="<?= $post_id; ?>">
            <input type="hidden" name="user_id" value="<?= $user_id; ?>">
            <button type="submit">Gửi</button>
        </form>
    <?php else: ?>
        <div class="comment-textarea">
            <p>Vui lòng <a href="/login?redirect_url=<?= urlencode($_SERVER['REQUEST_URI']) ?>">đăng nhập</a> để viết bình luận.</p>
        </div>
    <?php endif; ?>

    <ul class="comment-list">
        <?php foreach ($comments as $comment): ?>
            <li class="comment-list-item">
                <img src="<?php echo $comment['profile_image'] ?? ''; ?>" alt="Ảnh đại diện" class="comment-avatar">
                <div class="comment-content">
                    <span class="comment-username"><?php echo htmlspecialchars($comment['username']); ?></span>
                    <span class="comment-date"><?php echo htmlspecialchars($comment['created_at']); ?></span>
                    <p><?php echo htmlspecialchars($comment['content']); ?></p>
                    <?php if ($comment['user_id'] === $user_id): ?>
                        <form class="delete-comment-form" method="POST">
                            <input type="hidden" name="comment_id" value="<?= $comment['comment_id']; ?>">
                            <button type="submit">Xóa</button>
                        </form>
                    <?php endif; ?>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>
<?php if (!empty($user_id)) : ?>
    <script src="<?= Common::getAssetPath('embedded_scripts/user/comment.js.php') ?>?post_id=<?= $post_id ?>&user_id=<?= $user_id ?>&user_avatar=<?= $user['profile_picture'] ?? '' ?>&user_name=<?= urlencode($user['username'] ?? 'anon') ?>"></script>
<?php endif; ?>
