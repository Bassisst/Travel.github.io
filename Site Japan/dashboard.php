<?php
// Старт сессии
session_start();

// Проверка, авторизован ли пользователь
if (!isset($_SESSION['user_id'])) {
  // Если нет, перенаправляем на страницу логина
  header("Location: login.php");
  exit();
}

// Показать информацию о пользователе
echo "Добро пожаловать, " . $_SESSION['username'] . "!";
?>

