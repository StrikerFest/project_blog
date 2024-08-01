function likePost(postId) {
    $.ajax({
        url: 'ajax',
        type: 'POST',
        data: { action: 'like_post', post_id: postId },
        dataType: 'json',
        success: function(response) {
            if (response.status === 'redirect') {
                // Store the current URL in the session before redirecting
                $.ajax({
                    url: 'ajax',
                    type: 'POST',
                    data: { action: 'store_redirection_session', redirect_url: window.location.href },
                    success: function() {
                        window.location.href = '/login';
                    }
                });
            } else if (response.status === 'liked') {
                let likeButton = document.querySelector('.post-detail-like-button');
                likeButton.innerText = 'Liked';
                likeButton.dataset.liked = 'true';

                let likeCountElem = document.getElementById('like-count');
                let currentLikes = parseInt(likeCountElem.innerText);
                likeCountElem.innerText = (currentLikes + 1) + ' Likes';
            } else if (response.status === 'unliked') {
                let likeButton = document.querySelector('.post-detail-like-button');
                likeButton.innerText = 'Like';
                likeButton.dataset.liked = 'false';

                let likeCountElem = document.getElementById('like-count');
                let currentLikes = parseInt(likeCountElem.innerText);
                likeCountElem.innerText = (currentLikes - 1) + ' Likes';
            } else if (response.status === 'error') {
                console.error('Error:', response.message);
            }
        },
        error: function(xhr, status, error) {
            console.error('AJAX error:', error);
        }
    });
}