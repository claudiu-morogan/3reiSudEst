<?php
/**
 * Concert Model
 * Handles CRUD operations for concerts and events
 */

class Concert {
    /**
     * Get all concerts with optional filtering
     *
     * @param bool $includeUnpublished Include unpublished concerts
     * @param bool $upcomingOnly Only return upcoming/future concerts
     * @return array
     */
    public static function getAll(bool $includeUnpublished = false, bool $upcomingOnly = false): array {
        $sql = "SELECT * FROM concerts";
        $conditions = [];

        if (!$includeUnpublished) {
            $conditions[] = "is_published = 1";
        }

        if ($upcomingOnly) {
            $conditions[] = "event_date >= CURDATE()";
            $conditions[] = "status IN ('upcoming', 'sold_out')";
        }

        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }

        $sql .= " ORDER BY event_date ASC, event_time ASC";

        return Database::query($sql);
    }

    /**
     * Get upcoming concerts only
     */
    public static function getUpcoming(int $limit = 0): array {
        $sql = "SELECT * FROM concerts
                WHERE is_published = 1
                AND event_date >= CURDATE()
                AND status IN ('upcoming', 'sold_out')
                ORDER BY event_date ASC, event_time ASC";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit;
        }

        return Database::query($sql);
    }

    /**
     * Get past concerts
     */
    public static function getPast(int $limit = 0): array {
        $sql = "SELECT * FROM concerts
                WHERE is_published = 1
                AND (event_date < CURDATE() OR status = 'completed')
                ORDER BY event_date DESC, event_time DESC";

        if ($limit > 0) {
            $sql .= " LIMIT " . (int)$limit;
        }

        return Database::query($sql);
    }

    /**
     * Get single concert by ID
     */
    public static function getById(int $id): ?array {
        $sql = "SELECT * FROM concerts WHERE id = ?";
        return Database::queryOne($sql, [$id]);
    }

    /**
     * Create new concert
     */
    public static function create(array $data): int {
        $sql = "INSERT INTO concerts
                (title, venue, city, country, event_date, event_time, description,
                 ticket_url, venue_map_url, poster_image, price_info, status, is_published)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $params = [
            $data['title'],
            $data['venue'],
            $data['city'],
            $data['country'] ?? 'România',
            $data['event_date'],
            $data['event_time'] ?? null,
            $data['description'] ?? null,
            $data['ticket_url'] ?? null,
            $data['venue_map_url'] ?? null,
            $data['poster_image'] ?? null,
            $data['price_info'] ?? null,
            $data['status'] ?? 'upcoming',
            $data['is_published'] ?? 1
        ];

        return Database::execute($sql, $params);
    }

    /**
     * Update existing concert
     */
    public static function update(int $id, array $data): int {
        $sql = "UPDATE concerts SET
                title = ?,
                venue = ?,
                city = ?,
                country = ?,
                event_date = ?,
                event_time = ?,
                description = ?,
                ticket_url = ?,
                venue_map_url = ?,
                poster_image = ?,
                price_info = ?,
                status = ?,
                is_published = ?
                WHERE id = ?";

        $params = [
            $data['title'],
            $data['venue'],
            $data['city'],
            $data['country'] ?? 'România',
            $data['event_date'],
            $data['event_time'] ?? null,
            $data['description'] ?? null,
            $data['ticket_url'] ?? null,
            $data['venue_map_url'] ?? null,
            $data['poster_image'] ?? null,
            $data['price_info'] ?? null,
            $data['status'] ?? 'upcoming',
            $data['is_published'] ?? 1,
            $id
        ];

        return Database::execute($sql, $params);
    }

    /**
     * Delete concert
     */
    public static function delete(int $id): int {
        $sql = "DELETE FROM concerts WHERE id = ?";
        return Database::execute($sql, [$id]);
    }

    /**
     * Check if concert is in the past
     */
    public static function isPast(array $concert): bool {
        return strtotime($concert['event_date']) < strtotime('today');
    }

    /**
     * Format concert date for display
     */
    public static function formatDate(string $date): string {
        $timestamp = strtotime($date);
        $months = [
            1 => 'ianuarie', 2 => 'februarie', 3 => 'martie', 4 => 'aprilie',
            5 => 'mai', 6 => 'iunie', 7 => 'iulie', 8 => 'august',
            9 => 'septembrie', 10 => 'octombrie', 11 => 'noiembrie', 12 => 'decembrie'
        ];

        $day = date('j', $timestamp);
        $month = $months[(int)date('n', $timestamp)];
        $year = date('Y', $timestamp);

        return "$day $month $year";
    }

    /**
     * Format time for display
     */
    public static function formatTime(string $time): string {
        if (empty($time)) {
            return '';
        }
        return date('H:i', strtotime($time));
    }

    /**
     * Get status badge info
     */
    public static function getStatusBadge(string $status): array {
        $badges = [
            'upcoming' => ['text' => 'Viitor', 'class' => 'badge-primary'],
            'sold_out' => ['text' => 'Sold Out', 'class' => 'badge-warning'],
            'cancelled' => ['text' => 'Anulat', 'class' => 'badge-danger'],
            'completed' => ['text' => 'Trecut', 'class' => 'badge-secondary']
        ];

        return $badges[$status] ?? ['text' => $status, 'class' => 'badge-secondary'];
    }
}
