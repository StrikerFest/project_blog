<?php

namespace inc\models;

use inc\helpers\DB;

class Banner
{
    public static function getBanners($includeDeleted = false)
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM banners";
        if (!$includeDeleted) {
            $sql .= " WHERE deleted_at IS NULL";
        }
        $result = $conn->query($sql);
        $banners = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $row['type_name'] = self::getBannerTypeById($row['type_id']); // Get banner type name
                $banners[] = $row;
            }
        }
        $conn->close();
        return $banners;
    }

    public static function getBannerTypes()
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM banner_types WHERE deleted_at IS NULL";
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

        // Handle image upload
        $imagePath = self::handleImageUpload($files['image'], $data['existing_image_path']);

        // Format start_date and end_date to ensure proper formatting
        $start_date = self::formatDateTime($data['start_date']);
        $end_date = self::formatDateTime($data['end_date']);

        if (isset($data['id']) && !empty($data['id'])) {
            // Update existing banner
            $sql = "UPDATE banners SET title = ?, image_path = ?, text = ?, link = ?, start_date = ?, end_date = ?, is_active = ?, type_id = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('ssssssiii', $data['title'], $imagePath, $data['text'], $data['link'], $start_date, $end_date, $data['is_active'], $data['type_id'], $data['id']);
        } else {
            // Create new banner
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
            // If the date format is incorrect, return a null value or handle the exception as needed
            return null;
        }
    }

    public static function handleImageUpload($imageFile, $existingImagePath)
    {
        // Check if an image file was uploaded
        if (isset($imageFile) && $imageFile['error'] == UPLOAD_ERR_OK) {
            $uploadDir = $_ENV['UPLOAD_DIR']; // Directory to store uploaded files
            $relativeUploadDir = '/assets/uploads/'; // Relative path to store in the database
            $imageFileName = basename($imageFile['name']);
            $imageFullPath = $uploadDir . $imageFileName;

            // Move the uploaded file to the target directory
            if (move_uploaded_file($imageFile['tmp_name'], $imageFullPath)) {
                return $relativeUploadDir . $imageFileName;
            } else {
                throw new \Exception('Failed to upload image.');
            }
        }

        // If no new image was uploaded, return the existing image path
        return $existingImagePath;
    }

    public static function getBannerById($id)
    {
        $conn = DB::db_connect();
        $sql = "SELECT * FROM banners WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $banner = $result->fetch_assoc();

        if ($banner) {
            $banner['type_name'] = self::getBannerTypeById($banner['type_id']); // Get banner type name
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

    public static function getBannerTypeById($id)
    {
        $conn = DB::db_connect();
        $sql = "SELECT name FROM banner_types WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $type = $result->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $type['name'] ?? 'Unknown Type';
    }
}
