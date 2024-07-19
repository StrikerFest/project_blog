<?php
// Mock user data
use inc\helpers\Common;

$is_logged_in = true; // Change this to false to test the logged-out state
$user_avatar = Common::getAssetPath('images/avatar');
$user_name = 'John Doe';

// Mock comments data
$comments = [
    [
        'username' => 'Alice',
        'avatar' => $user_avatar,
        'content' => 'This is a great post!',
        'created_at' => '2024-07-19 12:34:56'
    ],
    [
        'username' => 'Bob',
        'avatar' => $user_avatar,
        'content' => 'I found this very helpful, thank you!',
        'created_at' => '2024-07-19 13:45:12'
    ]
];
?>
<div class="comment-section">
    <?php if ($is_logged_in): ?>
        <div class="comment-textarea">
            <img src="<?php echo $user_avatar; ?>" alt="User Avatar" class="comment-avatar">
            <label>
                <textarea placeholder="Write a comment..."></textarea>
            </label>
        </div>
    <?php else: ?>
        <div class="comment-textarea">
            <p>Please <a href="/login.php">log in</a> to write a comment.</p>
        </div>
    <?php endif; ?>

    <ul class="comment-list">
        <?php foreach ($comments as $comment): ?>
            <li class="comment-list-item">
                <img src="<?php echo $comment['avatar']; ?>" alt="<?php echo $comment['username']; ?>'s Avatar" class="comment-avatar">
                <div class="comment-content">
                    <span class="comment-username"><?php echo $comment['username']; ?></span>
                    <span class="comment-date"><?php echo $comment['created_at']; ?></span>
                    <p><?php echo $comment['content']; ?></p>
                </div>
            </li>
        <?php endforeach; ?>
    </ul>
</div>