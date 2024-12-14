<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "user_registration";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die(json_encode(['success' => false, 'message' => "Connection failed: " . $conn->connect_error]));
}

// Process form data
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $username = trim($_POST['username']);
  $password = $_POST['password'];
  $email = trim($_POST['email']);

  // Server-side validation
  if (strlen($username) < 3) {
    echo json_encode(['success' => false, 'message' => 'Username must be at least 3 characters long.']);
    exit;
  }

  if (strlen($password) < 6) {
    echo json_encode(['success' => false, 'message' => 'Password must be at least 6 characters long.']);
    exit;
  }

  if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email format.']);
    exit;
  }

  // Check if username or email already exists
  $check_query = "SELECT * FROM users WHERE username = ? OR email = ?";
  $stmt_check = $conn->prepare($check_query);
  $stmt_check->bind_param("ss", $username, $email);
  $stmt_check->execute();
  $result = $stmt_check->get_result();

  if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Username or email already exists.']);
  } else {
    // Hash password
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // SQL query to add new user
    $sql = "INSERT INTO users (username, password, email) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $username, $hashed_password, $email);

    if ($stmt->execute()) {
      echo json_encode(['success' => true, 'message' => 'Registration successful!']);
    } else {
      echo json_encode(['success' => false, 'message' => 'Error: ' . $stmt->error]);
    }

    $stmt->close();
  }

  $stmt_check->close();
}

$conn->close();
?>


