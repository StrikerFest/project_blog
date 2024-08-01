<?php

namespace inc\helpers\user;

use inc\models\Category;
use inc\models\Tag;

class Post
{
    // Thông tin phân trang lấy từ URL 
    public static function getPaginationParams($defaultPerPage = 8): array
    {
        $validPostsPerPage = [8, 16, 25];
        $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $postsPerPage = isset($_GET['per_page']) ? (int)$_GET['per_page'] : $defaultPerPage;

        if (!in_array($postsPerPage, $validPostsPerPage)) {
            $postsPerPage = $defaultPerPage;
        }

        return [$currentPage, $postsPerPage, $validPostsPerPage];
    }

    // Tạo link phân trang
    private static function getPaginationUrl($page, $perPage): string
    {
        $query = $_GET['query'] ?? '';
        $baseUrl = "?page=$page&per_page=$perPage";
        if ($query !== '') {
            $baseUrl .= "&query=" . urlencode($query);
        }
        return $baseUrl;
    }

    // Tạo template phân trang
    public static function generatePagination($currentPage, $totalPages, $postsPerPage): string
    {
        $pagination = '';

        if ($currentPage > 1) {
            $pagination .= '<a href="' . self::getPaginationUrl(1, $postsPerPage) . '"><< First</a>';
            $pagination .= '<a href="' . self::getPaginationUrl($currentPage - 1, $postsPerPage) . '">< Previous</a>';
        }

        for ($i = max(1, $currentPage - 2); $i < $currentPage; $i++) {
            $pagination .= '<a href="' . self::getPaginationUrl($i, $postsPerPage) . '">' . $i . '</a>';
        }

        $pagination .= '<a href="' . self::getPaginationUrl($currentPage, $postsPerPage) . '" class="current">' . $currentPage . '</a>';

        for ($i = $currentPage + 1; $i <= min($currentPage + 2, $totalPages); $i++) {
            $pagination .= '<a href="' . self::getPaginationUrl($i, $postsPerPage) . '">' . $i . '</a>';
        }

        if ($currentPage < $totalPages) {
            $pagination .= '<a href="' . self::getPaginationUrl($currentPage + 1, $postsPerPage) . '">Next ></a>';
            $pagination .= '<a href="' . self::getPaginationUrl($totalPages, $postsPerPage) . '">Last >></a>';
        }

        return $pagination;
    }
}
