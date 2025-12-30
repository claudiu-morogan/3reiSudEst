<?php
/**
 * Database Connection Wrapper
 * Singleton PDO wrapper with error handling and prepared statement helpers
 */

class Database {
    private static ?PDO $instance = null;

    /**
     * Get singleton PDO instance
     */
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            try {
                $dsn = sprintf(
                    'mysql:host=%s;dbname=%s;charset=%s',
                    DB_HOST,
                    DB_NAME,
                    DB_CHARSET
                );

                $options = [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                ];

                self::$instance = new PDO($dsn, DB_USER, DB_PASS, $options);

                // Force UTF-8 encoding for proper Romanian character display
                self::$instance->exec("SET NAMES utf8mb4");
                self::$instance->exec("SET CHARACTER SET utf8mb4");
            } catch (PDOException $e) {
                self::logError('Database connection failed: ' . $e->getMessage());
                die('Database connection error. Please check your configuration.');
            }
        }

        return self::$instance;
    }

    /**
     * Execute a SELECT query and return all results
     */
    public static function query(string $sql, array $params = []): array {
        try {
            $db = self::getInstance();
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll();
        } catch (PDOException $e) {
            self::logError('Query error: ' . $e->getMessage() . ' | SQL: ' . $sql);
            return [];
        }
    }

    /**
     * Execute a SELECT query and return single row
     */
    public static function queryOne(string $sql, array $params = []): ?array {
        try {
            $db = self::getInstance();
            $stmt = $db->prepare($sql);
            $stmt->execute($params);
            $result = $stmt->fetch();
            return $result ?: null;
        } catch (PDOException $e) {
            self::logError('Query error: ' . $e->getMessage() . ' | SQL: ' . $sql);
            return null;
        }
    }

    /**
     * Execute an INSERT/UPDATE/DELETE query
     * Returns last insert ID for INSERT, affected rows for UPDATE/DELETE
     */
    public static function execute(string $sql, array $params = []): int {
        try {
            $db = self::getInstance();
            $stmt = $db->prepare($sql);
            $stmt->execute($params);

            // Return last insert ID if INSERT, otherwise row count
            $lastId = $db->lastInsertId();
            return $lastId ? (int)$lastId : $stmt->rowCount();
        } catch (PDOException $e) {
            self::logError('Execute error: ' . $e->getMessage() . ' | SQL: ' . $sql);
            return 0;
        }
    }

    /**
     * Begin transaction
     */
    public static function beginTransaction(): bool {
        return self::getInstance()->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public static function commit(): bool {
        return self::getInstance()->commit();
    }

    /**
     * Rollback transaction
     */
    public static function rollback(): bool {
        return self::getInstance()->rollBack();
    }

    /**
     * Log errors (simple file logging)
     */
    private static function logError(string $message): void {
        if (APP_ENV === 'development') {
            error_log($message);
        } else {
            $logDir = APP_ROOT . '/logs';
            if (!is_dir($logDir)) {
                @mkdir($logDir, 0755, true);
            }
            $logFile = $logDir . '/db_errors.log';
            $timestamp = date('Y-m-d H:i:s');
            @file_put_contents($logFile, "[$timestamp] $message\n", FILE_APPEND);
        }
    }
}
