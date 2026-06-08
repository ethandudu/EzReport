<?php

class Database
{
    private static ?PDO $instance = null;

    private const HOST = 'mariadb';
    private const DBNAME = 'ezreport';
    private const USER = 'root';
    private const PASSWORD = 'rootpassword';

    private function __construct()
    {
    }

    public static function getConnection(): PDO
    {
        if (self::$instance === null) {
            try {
                self::$instance = new PDO(
                    sprintf(
                        'mysql:host=%s;dbname=%s;charset=utf8mb4',
                        self::HOST,
                        self::DBNAME
                    ),
                    self::USER,
                    self::PASSWORD,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                die('Erreur de connexion à la base de données : ' . $e->getMessage());
            }
        }

        return self::$instance;
    }

    public static function getTests(): array
    {
        $pdo = self::getConnection();
        $stmt = $pdo->query('SELECT DISTINCT testName FROM tests ORDER BY testName');
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public static function getTestResults(string $testName): array
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare('SELECT * FROM tests WHERE testName = :testName ORDER BY timestamp DESC');
        $stmt->execute(['testName' => self::sanitize($testName)]);
        return $stmt->fetchAll();
    }

    public static function insertTestResult(string $testName, string $value): void
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare('INSERT INTO tests (testName, value) VALUES (:testName, :value)');
        $stmt->execute(['testName' => self::sanitize($testName), 'value' => self::sanitize($value)]);
    }

    public static function deleteTestResults(string $testName): void
    {
        $pdo = self::getConnection();
        $stmt = $pdo->prepare('DELETE FROM tests WHERE testName = :testName');
        $stmt->execute(['testName' => self::sanitize($testName)]);
    }

    private static function sanitize(string $input): string
    {
        return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
    }
}