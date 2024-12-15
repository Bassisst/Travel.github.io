<?php
header('Content-Type: application/json');

// Połącz się z bazą danych
$servername = "localhost";
$username = "aarele_root";
$password = "O67id5t1!";
$dbname = "aarele_database";

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdź połączenie
if ($conn->connect_error) {
  die(json_encode(['success' => false, 'message' => "Ошибка подключения: " . $conn->connect_error]));
}

// Przetwarzaj dane formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];

// Mieszanie hasła
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Zapytanie SQL w celu dodania danych
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $hashed_password, $email);

  if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Регистрация прошла успешно!']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Ошибка: ' . $stmt->error]);
  }

  $stmt->close();
}
$conn->close();
?>


