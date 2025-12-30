<?php
/**
 * News Model
 * Handles CRUD operations for news articles
 */

class News {
    /**
     * Get all published news ordered by date
     */
    public static function getAll(bool $includeUnpublished = false): array {
        $sql = "SELECT * FROM news";
        
        if (!$includeUnpublished) {
            $sql .= " WHERE is_published = 1";
        }
        
        $sql .= " ORDER BY publish_date DESC";
        
        return Database::query($sql);
    }

    /**
     * Get single news by ID
     */
    public static function getById(int $id): ?array {
        $sql = "SELECT * FROM news WHERE id = ?";
        return Database::queryOne($sql, [$id]);
    }

    /**
     * Get single news by slug
     */
    public static function getBySlug(string $slug): ?array {
        $sql = "SELECT * FROM news WHERE slug = ? AND is_published = 1";
        return Database::queryOne($sql, [$slug]);
    }

    /**
     * Get latest news (limit)
     */
    public static function getLatest(int $limit = 5): array {
        $sql = "SELECT * FROM news WHERE is_published = 1 ORDER BY publish_date DESC LIMIT ?";
        return Database::query($sql, [$limit]);
    }

    /**
     * Create new news article
     */
    public static function create(array $data): int {
        $sql = "INSERT INTO news (title, slug, excerpt, content, featured_image, publish_date, is_published) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['title'],
            $data['slug'] ?? self::generateSlug($data['title']),
            $data['excerpt'] ?? null,
            $data['content'],
            $data['featured_image'] ?? null,
            $data['publish_date'] ?? date('Y-m-d'),
            $data['is_published'] ?? 1
        ];
        
        return Database::execute($sql, $params);
    }

    /**
     * Update existing news article
     */
    public static function update(int $id, array $data): int {
        $sql = "UPDATE news SET 
                title = ?, 
                slug = ?, 
                excerpt = ?, 
                content = ?, 
                featured_image = ?, 
                publish_date = ?, 
                is_published = ? 
                WHERE id = ?";
        
        $params = [
            $data['title'],
            $data['slug'] ?? self::generateSlug($data['title']),
            $data['excerpt'] ?? null,
            $data['content'],
            $data['featured_image'] ?? null,
            $data['publish_date'] ?? date('Y-m-d'),
            $data['is_published'] ?? 1,
            $id
        ];
        
        return Database::execute($sql, $params);
    }

    /**
     * Delete news article
     */
    public static function delete(int $id): int {
        $sql = "DELETE FROM news WHERE id = ?";
        return Database::execute($sql, [$id]);
    }

    /**
     * Generate URL-friendly slug from title
     */
    private static function generateSlug(string $title): string {
        // Romanian character mapping
        $romanianChars = [
            'ă' => 'a', 'â' => 'a', 'î' => 'i', 'ș' => 's', 'ț' => 't',
            'Ă' => 'a', 'Â' => 'a', 'Î' => 'i', 'Ș' => 's', 'Ț' => 't'
        ];
        
        $slug = str_replace(array_keys($romanianChars), array_values($romanianChars), $title);
        $slug = strtolower($slug);
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug);
        $slug = trim($slug, '-');
        
        // Ensure uniqueness
        $original = $slug;
        $counter = 1;
        while (self::slugExists($slug)) {
            $slug = $original . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }

    /**
     * Check if slug already exists
     */
    private static function slugExists(string $slug): bool {
        $sql = "SELECT COUNT(*) as count FROM news WHERE slug = ?";
        $result = Database::queryOne($sql, [$slug]);
        return $result['count'] > 0;
    }
}
