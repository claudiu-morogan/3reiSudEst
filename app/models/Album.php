<?php
/**
 * Album Model
 * Handles CRUD operations for albums and tracks
 */

class Album {
    /**
     * Get all published albums ordered by sort_order or year
     */
    public static function getAll(bool $includeUnpublished = false): array {
        $sql = "SELECT * FROM albums";
        
        if (!$includeUnpublished) {
            $sql .= " WHERE is_published = 1";
        }
        
        $sql .= " ORDER BY sort_order ASC, release_year DESC";
        
        return Database::query($sql);
    }

    /**
     * Get single album by ID
     */
    public static function getById(int $id): ?array {
        $sql = "SELECT * FROM albums WHERE id = ?";
        return Database::queryOne($sql, [$id]);
    }

    /**
     * Get album with all tracks
     */
    public static function getWithTracks(int $id): ?array {
        $album = self::getById($id);
        
        if (!$album) {
            return null;
        }
        
        $tracksSql = "SELECT * FROM tracks WHERE album_id = ? ORDER BY track_number ASC";
        $album['tracks'] = Database::query($tracksSql, [$id]);
        
        return $album;
    }

    /**
     * Create new album
     */
    public static function create(array $data): int {
        $sql = "INSERT INTO albums (title, release_year, cover_image, description, spotify_url, apple_music_url, sort_order, is_published) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        
        $params = [
            $data['title'],
            $data['release_year'],
            $data['cover_image'] ?? null,
            $data['description'] ?? null,
            $data['spotify_url'] ?? null,
            $data['apple_music_url'] ?? null,
            $data['sort_order'] ?? 0,
            $data['is_published'] ?? 1
        ];
        
        return Database::execute($sql, $params);
    }

    /**
     * Update existing album
     */
    public static function update(int $id, array $data): int {
        $sql = "UPDATE albums SET 
                title = ?, 
                release_year = ?, 
                cover_image = ?, 
                description = ?, 
                spotify_url = ?, 
                apple_music_url = ?, 
                sort_order = ?, 
                is_published = ? 
                WHERE id = ?";
        
        $params = [
            $data['title'],
            $data['release_year'],
            $data['cover_image'] ?? null,
            $data['description'] ?? null,
            $data['spotify_url'] ?? null,
            $data['apple_music_url'] ?? null,
            $data['sort_order'] ?? 0,
            $data['is_published'] ?? 1,
            $id
        ];
        
        return Database::execute($sql, $params);
    }

    /**
     * Delete album (and cascading tracks)
     */
    public static function delete(int $id): int {
        $sql = "DELETE FROM albums WHERE id = ?";
        return Database::execute($sql, [$id]);
    }

    /**
     * Add track to album
     */
    public static function addTrack(int $albumId, array $trackData): int {
        $sql = "INSERT INTO tracks (album_id, title, track_number, duration, youtube_url) 
                VALUES (?, ?, ?, ?, ?)";
        
        $params = [
            $albumId,
            $trackData['title'],
            $trackData['track_number'],
            $trackData['duration'] ?? null,
            $trackData['youtube_url'] ?? null
        ];
        
        return Database::execute($sql, $params);
    }

    /**
     * Delete track
     */
    public static function deleteTrack(int $trackId): int {
        $sql = "DELETE FROM tracks WHERE id = ?";
        return Database::execute($sql, [$trackId]);
    }
}
