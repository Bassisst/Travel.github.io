<?php
// Подключение к базе данных
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_registration";

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

  // Проверка, существует ли уже логин или email
  $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
  $stmt_check = $conn->prepare($check_query);
  $stmt_check->bind_param("ss", $username, $email);
  $stmt_check->execute();
  $result = $stmt_check->get_result();

  if ($result->num_rows > 0) {
    // Если логин или email уже существуют, выводим ошибку
    echo "Ошибка: Логин или email уже заняты!";
  } else {
    // Хеширование пароля
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL-запрос для добавления нового пользователя
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

  $stmt_check->close();
}

$conn->close();
?>

