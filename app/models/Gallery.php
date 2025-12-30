<?php
/**
 * Gallery Model
 * Handles CRUD operations for gallery items
 */

class Gallery {
    /**
     * Get all published gallery items
     */
    public static function getAll(bool $includeUnpublished = false): array {
        $sql = "SELECT * FROM gallery_items";
        
        if (!$includeUnpublished) {
            $sql .= " WHERE is_published = 1";
        }
        
        $sql .= " ORDER BY sort_order ASC, created_at DESC";
        
        return Database::query($sql);
    }

    /**
     * Get single gallery item by ID
     */
    public static function getById(int $id): ?array {
        $sql = "SELECT * FROM gallery_items WHERE id = ?";
        return Database::queryOne($sql, [$id]);
    }

    /**
     * Create new gallery item
     */
    public static function create(array $data): int {
        $sql = "INSERT INTO gallery_items (title, image_path, thumbnail_path, caption, sort_order, is_published) 
                VALUES (?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['title'] ?? null,
            $data['image_path'],
            $data['thumbnail_path'] ?? null,
            $data['caption'] ?? null,
            $data['sort_order'] ?? 0,
            $data['is_published'] ?? 1
        ];
        
        return Database::execute($sql, $params);
    }

    /**
     * Update existing gallery item
     */
    public static function update(int $id, array $data): int {
        $sql = "UPDATE gallery_items SET 
                title = ?, 
                caption = ?, 
                sort_order = ?, 
                is_published = ? 
                WHERE id = ?";
        
        $params = [
            $data['title'] ?? null,
            $data['caption'] ?? null,
            $data['sort_order'] ?? 0,
            $data['is_published'] ?? 1,
            $id
        ];
        
        return Database::execute($sql, $params);
    }

    /**
     * Delete gallery item
     */
    public static function delete(int $id): int {
        // Get image paths before deletion
        $item = self::getById($id);
        
        if ($item) {
            // Delete physical files
            if ($item['image_path'] && file_exists(APP_ROOT . '/' . $item['image_path'])) {
                @unlink(APP_ROOT . '/' . $item['image_path']);
            }
            if ($item['thumbnail_path'] && file_exists(APP_ROOT . '/' . $item['thumbnail_path'])) {
                @unlink(APP_ROOT . '/' . $item['thumbnail_path']);
            }
        }
        
        $sql = "DELETE FROM gallery_items WHERE id = ?";
        return Database::execute($sql, [$id]);
    }
}
