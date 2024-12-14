document.addEventListener('DOMContentLoaded', function() {
  const form = document.getElementById('registerForm');
  const messageDiv = document.getElementById('message');

  form.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const username = document.getElementById('username').value;
    const password = document.getElementById('password').value;
    const email = document.getElementById('email').value;

    // Basic client-side validation
    if (username.length < 3) {
      showMessage('Username must be at least 3 characters long.', 'error');
      return;
    }

    if (password.length < 6) {
      showMessage('Password must be at least 6 characters long.', 'error');
      return;
    }

    if (!isValidEmail(email)) {
      showMessage('Please enter a valid email address.', 'error');
      return;
    }

    // Check if username or email already exists
    const users = JSON.parse(localStorage.getItem('users')) || [];
    if (users.some(user => user.username === username || user.email === email)) {
      showMessage('Username or email already exists.', 'error');
      return;
    }

    // Add new user
    users.push({ username, email, password });
    localStorage.setItem('users', JSON.stringify(users));

    showMessage('Registration successful! You can now log in.', 'success');
    form.reset();
  });

  function showMessage(message, type) {
    messageDiv.textContent = message;
    messageDiv.className = type === 'error' ? 'error-message' : 'success-message';
  }

  function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }
});

