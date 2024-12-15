<?php
// Подключение к базе данных
$servername = "localhost";
$username = "aarele_root";
$password = "O67id5t1!";
$dbname = "aarele_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Проверяем подключение
if ($conn->connect_error) {
  die("Ошибка подключения: " . $conn->connect_error);
}

// Обработка данных формы
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];

  // Хеширование пароля
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

  // SQL-запрос для добавления данных
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $hashed_password, $email);

  if ($stmt->execute()) {
    echo "Регистрация прошла успешно!";
  } else {
    echo "Ошибка: " . $stmt->error;
  }

  $stmt->close();
}
$conn->close();
?>

