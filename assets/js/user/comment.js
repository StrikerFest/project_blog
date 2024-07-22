$(document).ready(function() {
    $('#comment-form').on('submit', function(event) {
        event.preventDefault(); // Prevent the form from submitting the traditional way

        $.ajax({
            url: 'comment',
            type: 'POST',
            data: $(this).serialize(),
            dataType: 'json',
            success: function(data) {
                if (data.success) {
                    // Create a new comment element
                    const newComment = $('<li class="comment-list-item"></li>');
                    newComment.html(`
                        <img src="<?php echo $user_avatar; ?>" alt="Avatar" class="comment-avatar">
                        <div class="comment-content">
                            <span class="comment-username"><?php echo $user_name; ?></span>
                            <span class="comment-date">${new Date().toISOString().slice(0, 19).replace('T', ' ')}</span>
                            <p>${data.comment}</p>
                        </div>
                    `);
                    $('.comment-list').append(newComment);

                    // Clear the textarea
                    $('textarea[name="comment"]').val('');
                } else {
                    alert('There was an error submitting your comment. Please try again.');
                }
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
                alert('There was an error submitting your comment. Please try again.');
            }
        });
    });
});