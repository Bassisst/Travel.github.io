<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "aarele_root";
$password = "O67id5t1!";
$dbname = "aarele_database";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
  die(json_encode(['success' => false, 'message' => "Błąd połączenia: " . $conn->connect_error]));
}

$response = ['success' => false, 'message' => ''];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $user = $_POST['username'] ?? '';
  $pass = $_POST['password'] ?? '';

  if (empty($user) || empty($pass)) {
    $response['message'] = "Proszę podać nazwę użytkownika i hasło";
  } else {
    $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $user, $user);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
      $row = $result->fetch_assoc();
      if (password_verify($pass, $row['password'])) {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_id'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        $response['success'] = true;
        $response['message'] = "Logowanie udane";
      } else {
        $response['message'] = "Nieprawidłowe hasło";
      }
    } else {
      $response['message'] = "Nie znaleziono użytkownika";
    }
    $stmt->close();
  }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>




