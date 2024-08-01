<?php
require $_ENV['AUTOLOAD'];

use inc\helpers\Common;
use inc\models\Post;

// Check if the search query is set and not empty
$search_query = $_GET['query'] ?? '';

if (!empty($search_query)) {
    // Fetch posts based on the search query
    $posts = Post::searchPosts($search_query);
} else {
    // Redirect to the index page if no search query is provided
    header('Location: /');
    exit();
}

// Import search results template
Common::requireTemplate('user/post/search_results.php', [
    'search_term' => $search_query,
    'posts' => $posts,
]);

exit;
