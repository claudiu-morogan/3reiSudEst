<?php
/**
 * Biography Model
 * Handles CRUD operations for biography sections
 */

class Biography {
    /**
     * Get all published biography sections
     */
    public static function getAll(bool $includeUnpublished = false): array {
        $sql = "SELECT * FROM biography_sections";
        
        if (!$includeUnpublished) {
            $sql .= " WHERE is_published = 1";
        }
        
        $sql .= " ORDER BY sort_order ASC";
        
        return Database::query($sql);
    }

    /**
     * Get single biography section by ID
     */
    public static function getById(int $id): ?array {
        $sql = "SELECT * FROM biography_sections WHERE id = ?";
        return Database::queryOne($sql, [$id]);
    }

    /**
     * Create new biography section
     */
    public static function create(array $data): int {
        $sql = "INSERT INTO biography_sections (title, content, sort_order, is_published) 
                VALUES (?, ?, ?, ?)";
        
        $params = [
            $data['title'],
            $data['content'],
            $data['sort_order'] ?? 0,
            $data['is_published'] ?? 1
        ];
        
        return Database::execute($sql, $params);
    }

    /**
     * Update existing biography section
     */
    public static function update(int $id, array $data): int {
        $sql = "UPDATE biography_sections SET 
                title = ?, 
                content = ?, 
                sort_order = ?, 
                is_published = ? 
                WHERE id = ?";
        
        $params = [
            $data['title'],
            $data['content'],
            $data['sort_order'] ?? 0,
            $data['is_published'] ?? 1,
            $id
        ];
        
        return Database::execute($sql, $params);
    }

    /**
     * Delete biography section
     */
    public static function delete(int $id): int {
        $sql = "DELETE FROM biography_sections WHERE id = ?";
        return Database::execute($sql, [$id]);
    }
}
