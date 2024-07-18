<?php

namespace inc\helpers\admin;

use inc\models\Category;
use inc\models\Tag;

class Post
{
    public static function canChangeStatus($currentStatus, $role): bool
    {
        $statusPermissions = [
            'author' => ['draft', 'approval_retracted', 'approval_denied', 'pending_approval'],
            'editor' => ['pending_approval', 'approval_denied', 'approved', 'published', 'approval_retracted', 'published_retracted'],
            'admin' => ['draft', 'pending_approval', 'approval_denied', 'approved', 'published', 'approval_retracted', 'published_retracted'],
        ];

        // Post being created
        if ($currentStatus === null) {
            if ($role == 'editor') {
                return false;
            }
            return true;
        }

        if (isset($statusPermissions[$role])) {
            return in_array($currentStatus, $statusPermissions[$role]);
        }

        return false;
    }

    public static function getPostCategories($post_id, $display = 'name', $limit = 3): array
    {
        if ($post_id === null) {
            return [];
        }

        $data = Category::getPostCategoryIds($post_id);

        if (empty($data)) {
            return [];
        }

        $result = [];
        if ($display == 'name') {
            foreach ($data as $category) {
                $result[] = $category['name'];
            }
        } else {
            foreach ($data as $category) {
                $result[] = $category['category_id'];
            }
        }

        if ($limit === 0){
            return $result;
        }
        
        return array_slice($result, 0, $limit);
    }


    public static function getPostTags($post_id, $display = 'name', $limit = 3): array
    {
        if ($post_id === null) {
            return [];
        }

        $data = Tag::getPostTagIds($post_id);

        if (empty($data)) {
            return [];
        }

        $result = [];
        if ($display == 'name') {
            foreach ($data as $tag) {
                $result[] = $tag['name'];
            }
        } else {
            foreach ($data as $tag) {
                $result[] = $tag['tag_id'];
            }
        }

        if ($limit === 0){
            return $result;
        }

        return array_slice($result, 0, $limit);

    }

}
