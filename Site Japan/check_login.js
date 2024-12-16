async function checkLoginStatus() {
  try {
    const response = await fetch('check_session.php');
    if (!response.ok) {
      throw new Error('Błąd sieci');
    }
    const data = await response.json();
    if (data.logged_in) {
      document.getElementById('my-account-tab').style.display = 'block';
    } else {
      document.getElementById('my-account-tab').style.display = 'none';
    }
  } catch (error) {
    console.error('Błąd:', error);
  }
}

document.addEventListener('DOMContentLoaded', checkLoginStatus);

