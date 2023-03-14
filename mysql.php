<?php

// Підключення до бази даних
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// перевірка з'єднання
if (!$conn) {
    die("Connection failed: " . $conn->connect_error());
}

// Встановлення кодування
$conn->set_charset("utf8mb4");

?>

