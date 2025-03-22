<?php
declare(strict_types=1);

namespace Classes;

use PDO;
use PDOException;

class Database {
    private static ?PDO $pdo = null;

    public static function getConnection(): PDO {
        if (self::$pdo === null) {
            $config = require __DIR__ . '/../config/config.php';
            try {
                self::$pdo = new PDO(
                    "mysql:host={$config['host']};dbname={$config['dbname']};charset=utf8",
                    $config['user'],
                    $config['pass'],
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                    ]
                );
            } catch (PDOException $e) {
                die("Erro na conexão: " . $e->getMessage());
            }
        }
        return self::$pdo;
    }

    // Método seguro para SELECT
    public static function select(string $query, array $params = []): array {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    // Método seguro para INSERT
    public static function insert(string $query, array $params = []): bool {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare($query);
        return $stmt->execute($params);
    }

}
