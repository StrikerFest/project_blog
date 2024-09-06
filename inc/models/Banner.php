<?php

namespace inc\models;

use inc\helpers\Common;
use inc\helpers\DB;

class Banner
{
    public static function getBanners($checkActive = true, $checkDeleted = true)
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM banners WHERE 1";

        if ($checkActive) {
            $sql .= " AND is_active = 1";
        }
        if ($checkDeleted) {
            $sql .= " AND deleted_at IS NULL";
        }

        $result = $conn->query($sql);
        $banners = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['type_name'] = self::getBannerTypeById($row['type_id']);
                $banners[] = $row;
            }
        }
        $conn->close();
        return $banners;
    }

    public static function getBannerTypes($checkDeleted = true)
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM banner_types WHERE 1";

        if ($checkDeleted) {
            $sql .= " AND deleted_at IS NULL";
        }

        $result = $conn->query($sql);
        $types = $result->fetch_all(MYSQLI_ASSOC);

        $conn->close();
        return $types;
    }

    public static function deleteBanner($id)
    {
        $conn = DB::db_connect();
        $sql = "UPDATE banners SET deleted_at = NOW() WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $stmt->close();

        $conn->close();
    }

    public static function saveBanner($data, $files)
    {
        $conn = DB::db_connect();
        $imagePath = self::handleImageUpload($files['image'], $data['existing_image_path']);
        $start_date = self::formatDateTime($data['start_date']);
        $end_date = self::formatDateTime($data['end_date']);

        if (isset($data['id']) && !empty($data['id'])) {
            $sql = "UPDATE banners SET title = ?, image_path = ?, text = ?, link = ?, start_date = ?, end_date = ?, is_active = ?, type_id = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssiii', $data['title'], $imagePath, $data['text'], $data['link'], $start_date, $end_date, $data['is_active'], $data['type_id'], $data['id']);
        } else {
            $sql = "INSERT INTO banners (title, image_path, text, link, start_date, end_date, is_active, type_id, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssii', $data['title'], $imagePath, $data['text'], $data['link'], $start_date, $end_date, $data['is_active'], $data['type_id']);
        }

        $stmt->execute();
        $stmt->close();
        $conn->close();
    }

    private static function formatDateTime($dateTime)
    {
        try {
            $date = new \DateTime($dateTime);
            return $date->format('Y-m-d H:i:s');
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function handleImageUpload($imageFile, $existingImagePath)
    {
        if (isset($imageFile) && $imageFile['error'] == UPLOAD_ERR_OK) {
            $uploadDir = $_ENV['UPLOAD_DIR'];
            $relativeUploadDir = '/assets/uploads/';
            $imageFileName = basename($imageFile['name']);
            $imageFullPath = $uploadDir . $imageFileName;

            if (move_uploaded_file($imageFile['tmp_name'], $imageFullPath)) {
                return $relativeUploadDir . $imageFileName;
            } else {
                throw new \Exception('Failed to upload image.');
            }
        }

        return $existingImagePath;
    }

    public static function getBannerById($id, $checkActive = true, $checkDeleted = true)
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM banners WHERE id = ?";

        if ($checkActive) {
            $sql .= " AND is_active = 1";
        }
        if ($checkDeleted) {
            $sql .= " AND deleted_at IS NULL";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $banner = $result->fetch_assoc();

        if ($banner) {
            $banner['type_name'] = self::getBannerTypeById($banner['type_id']);
        }

        $stmt->close();
        $conn->close();

        return $banner;
    }

    public static function softDeleteBanner($id): bool
    {
        $conn = DB::db_connect();
        $sql = "UPDATE banners SET deleted_at = CURRENT_TIMESTAMP WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $result;
    }

    public static function recoverBanner($id): bool
    {
        $conn = DB::db_connect();
        $sql = "UPDATE banners SET deleted_at = NULL WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $result = $stmt->execute();
        $stmt->close();
        $conn->close();

        return $result;
    }

    public static function getBannerTypeById($id, $checkDeleted = true)
    {
        $conn = DB::db_connect();
        $sql = "SELECT name FROM banner_types WHERE id = ?";

        if ($checkDeleted) {
            $sql .= " AND deleted_at IS NULL";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $type = $result->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $type['name'] ?? 'Unknown Type';
    }

    public static function getBannerByType($type, $checkActive = true, $checkDeleted = true)
    {
        $conn = DB::db_connect();
        $currentDateTime = date('Y-m-d H:i:s');

        $sql = "
        SELECT * FROM banners 
        WHERE type_id = (SELECT id FROM banner_types WHERE name = ?) 
          AND start_date <= ? 
          AND end_date >= ?";

        if ($checkActive) {
            $sql .= " AND is_active = 1";
        }
        if ($checkDeleted) {
            $sql .= " AND deleted_at IS NULL";
        }

        $sql .= " ORDER BY position ASC, id DESC LIMIT 1";

        $stmt = $conn->prepare($sql);
        $stmt->bind_param('sss', $type, $currentDateTime, $currentDateTime);
        $stmt->execute();
        $result = $stmt->get_result();
        $banner = $result->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $banner;
    }

    public static function getBannerTemplate(string $path, array|null $bannerContent)
    {
        if (isset($bannerContent)) {
            Common::requireTemplate($path, [
                'banner_image' => $bannerContent['image_path'],
                'banner_link' => $bannerContent['link'] ?? '#'
            ]);
        }
    }
}
