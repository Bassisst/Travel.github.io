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
  die(json_encode(['success' => false, 'message' => "Błąd połączenia: " . $conn->connect_error]));
}

// Przetwarzaj dane formularza
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = $_POST['username'];
  $password = $_POST['password'];
  $email = $_POST['email'];

// Sprawdź, czy użytkownik o tej samej nazwie już istnieje
  $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
  $stmt->bind_param("s", $username);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Nazwa użytkownika jest już zajęta.']);
    $stmt->close();
    $conn->close();
    exit();
  }
  $stmt->close();

// Sprawdź, czy użytkownik o tym samym adresie e-mail już istnieje
  $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $result = $stmt->get_result();
  if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Adres email jest już zajęty.']);
    $stmt->close();
    $conn->close();
    exit();
  }
  $stmt->close();

// Hashowanie hasła
  $hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Zapytanie SQL w celu dodania danych
  $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
  $stmt = $conn->prepare($sql);
  $stmt->bind_param("sss", $username, $hashed_password, $email);

  if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Rejestracja przebiegła pomyślnie!']);
  } else {
    echo json_encode(['success' => false, 'message' => 'Błąd: ' . $stmt->error]);
  }

  $stmt->close();
}
$conn->close();
?>

