<?php

namespace inc\helpers;

use mysqli;

class DB {
    
    // Function to establish a database connection
    public static function db_connect() {
        $conn = new mysqli($_ENV['DB_HOST'], $_ENV['DB_USER'], $_ENV['DB_PASS'], $_ENV['DB_NAME']);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }
        if (!$conn->set_charset("utf8")) {
            die("Error loading character set utf8: " . $conn->error);
        }
        return $conn;
    }

    /**
     * Fetch a single row from the database.
     */
    public static function fetchSingleRow($sql, $params = [], $types = ""): ?array
    {
        $conn = DB::db_connect();
        $stmt = $conn->prepare($sql);

        if ($params && $types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        $stmt->close();
        $conn->close();

        return $row ?: null;
    }

    /**
     * Fetch all rows from the database.
     */
    public static function fetchAllRows($sql, $params = [], $types = ""): array
    {
        $conn = DB::db_connect();
        $stmt = $conn->prepare($sql);

        if ($params && $types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $rows = [];

        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }

        $stmt->close();
        $conn->close();

        return $rows;
    }

    /**
     * Execute a query (e.g., INSERT, UPDATE, DELETE) with parameters.
     */
    public static function executeQuery($sql, $params = [], $types = ""): bool
    {
        $conn = DB::db_connect();
        $stmt = $conn->prepare($sql);

        if ($params && $types) {
            $stmt->bind_param($types, ...$params);
        }

        $success = $stmt->execute();

        $stmt->close();
        $conn->close();

        return $success;
    }

    /**
     * Fetch a single value from the database.
     */
    public static function fetchSingleValue($sql, $params = [], $types = "")
    {
        $conn = DB::db_connect();
        $stmt = $conn->prepare($sql);

        if ($params && $types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $stmt->bind_result($value);
        $stmt->fetch();

        $stmt->close();
        $conn->close();

        return $value;
    }
}
