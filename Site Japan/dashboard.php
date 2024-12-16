<?php
session_start();

// Sprawdź, czy użytkownik jest autoryzowany
if (!isset($_SESSION['user_id'])) {
  header("Location: login.php");
  exit();
}

// Pokaż informacje o użytkowniku
echo "Powitanie, " . $_SESSION['username'] . "!";
?>

