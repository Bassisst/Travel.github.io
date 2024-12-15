async function checkLoginStatus() {
  try {
    const response = await fetch('check_login.php');
    const data = await response.json();
    const myAccountTab = document.getElementById('my-account-tab');

    if (data.logged_in) {
      myAccountTab.style.display = 'block';
    } else {
      myAccountTab.style.display = 'none';
    }
  } catch (error) {
    console.error('Error checking login status:', error);
  }
}

document.addEventListener('DOMContentLoaded', checkLoginStatus);

