<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = $_POST['name'];
  $email = $_POST['email'];
  $phone = $_POST['phone'];
  $message = $_POST['message'];

  $to = "vova30511@gmail.com";
  $subject = "Nowa wiadomość z formularza kontaktowego";

  $body = "Imię i Nazwisko: $name\n";
  $body .= "Email: $email\n";
  $body .= "Numer telefonu: $phone\n";
  $body .= "Wiadomość:\n$message\n";

  $headers = "From: $email\r\n";
  $headers .= "Reply-To: $email\r\n";

  if (mail($to, $subject, $body, $headers)) {
    echo "success";
  } else {
    http_response_code(500);
    echo "Wystąpił błąd podczas wysyłania wiadomości.";
  }
} else {
  http_response_code(403);
  echo "Dostęp zabroniony.";
}
?>


