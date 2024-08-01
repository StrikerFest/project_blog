<?php
header('Content-Type: application/javascript');

$post_id = $_GET['post_id'];
$user_id = $_GET['user_id'];
$user_avatar = $_GET['user_avatar'];
$user_name = $_GET['user_name'];
?>

$(document).ready(function () {

    $('#comment-form').on('submit', function (event) {
        event.preventDefault(); // Prevent the form from submitting the traditional way

        $.ajax({
            url: 'comment',
            type: 'POST',
            data: $(this).serialize() + '&action=save_comment',
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    // Create a new comment element
                    const newComment = $('<li class="comment-list-item"></li>');
                    newComment.html(`
                        <img src="<?php echo $user_avatar; ?>" alt="Avatar" class="comment-avatar">
                        <div class="comment-content">
                            <span class="comment-username"><?php echo $user_name; ?></span>
                            <span class="comment-date">${new Date().toISOString().slice(0, 19).replace('T', ' ')}</span>
                            <p>${data.comment}</p>
                            <form class="delete-comment-form" method="POST">
                                <input type="hidden" name="comment_id" value="${data.comment_id}">
                                <button type="submit">Delete</button>
                            </form>
                        </div>
                    `);
                    $('.comment-list').append(newComment);

                    // Clear the textarea
                    $('textarea[name="comment"]').val('');
                } else {
                    alert('There was an error submitting your comment. Please try again.');
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('There was an error submitting your comment. Please try again.');
            }
        });
    });

    // Delegate event for delete button since it's dynamically added
    $('.comment-list').on('submit', '.delete-comment-form', function (event) {
        event.preventDefault();
        const form = $(this);
        const comment_id = form.find('input[name="comment_id"]').val();

        $.ajax({
            url: 'comment',
            type: 'POST',
            data: {
                action: 'delete_comment',
                post_id: <?php echo $post_id; ?>,
                user_id: <?php echo $user_id; ?>,
                comment_id: comment_id
            },
            dataType: 'json',
            success: function (data) {
                if (data.success) {
                    form.closest('.comment-list-item').remove();
                } else {
                    alert('Failed to delete comment: ' + data.error);
                }
            },
            error: function (xhr, status, error) {
                console.error('Error:', error);
                alert('Failed to delete comment. Please try again.');
            }
        });
    });
});
