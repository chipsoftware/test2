<?php

session_start();

// підключення конфігурації
include_once 'config.php';

// з'єднання з базою даних
include_once 'mysql.php';

// завантаження даних категорій в БД
include_once 'skills.php';

// завантаження даних проєктів в БД
include_once 'projects.php';

// відображення даних проєктів 
include_once 'view.php';

// Закриття з'єднання з БД
mysqli_close($conn);
?>