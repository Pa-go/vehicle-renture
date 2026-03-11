<!--Updated themed login page (Deep Navy, Lighter Navy, Soft Gold, Soft Grey-Blue) --><html>
  <head>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
      body {
        background-color: #E6F0F4;
        margin: 0;
        font-family: 'Montserrat', sans-serif;
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: 100vh;
      }h1 {
    font-weight: bold;
    margin: 0;
    color: #001F3F; /* Primary Base: Deep Navy */
  }

  p {
    font-size: 14px;
    font-weight: 100;
    line-height: 20px;
    letter-spacing: 0.5px;
    margin: 20px 0 30px;
    color: #001F3F; /* Deep Navy */
  }

  span {
    font-size: 12px;
    color: #001F3F; /* Deep Navy */
  }

  a {
    color: #001F3F; /* Deep Navy */
    font-size: 14px;
    text-decoration: none;
    margin: 15px 0;
  }

  button {
    border-radius: 20px;
    border: 1px solid #FFD700; /* Soft Gold */
    background-color: #FFD700; /* CTA */
    color: #001F3F; /* Deep Navy text */
    font-size: 12px;
    font-weight: bold;
    padding: 12px 45px;
    letter-spacing: 1px;
    text-transform: uppercase;
    transition: transform 80ms ease-in;
  }

  button:active {
    transform: scale(0.95);
  }

  button:focus {
    outline: none;
  }

  button.ghost {
    background-color: transparent;
    border-color: #FFD700; /* Clean White on dark overlay */
    color: #FFFFFF;
  }

  form {
    background-color: #FFFFFF;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 20px 50px;
    height: 100%;
    text-align: center;
    gap: 10px;
  }

  input {
    background-color: #EEE;
    border: 1px solid #FFD700;
    padding: 12px 15px;
    width: 100%;
    border-radius: 8px;
    font-size: 14px;
  }

  .social-container {
    margin: 20px 0;
  }

  .social-container a {
    border: 1px solid #193857; /* Secondary Base */
    border-radius: 50%;
    display: inline-flex;
    justify-content: center;
    align-items: center;
    margin: 0 5px;
    height: 40px;
    width: 40px;
    color: #193857;
  }

  .container {
    background-color: #FFFFFF;
    border-radius: 20px;
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
    position: relative;
    overflow: hidden;
    width: 850px;
    max-width: 95%;
    min-height: 520px;
  }

  .form-container {
    position: absolute;
    top: 0;
    height: 100%;
    transition: all 0.6s ease-in-out;
  }

  .login-container {
    left: 0;
    width: 50%;
    z-index: 2;
  }

  .register-container {
    left: 0;
    width: 50%;
    opacity: 0;
    z-index: 1;
  }

  .container.right-panel-active .login-container {
    transform: translateX(100%);
  }

  .container.right-panel-active .register-container {
    transform: translateX(100%);
    opacity: 1;
    z-index: 5;
    animation: show 0.6s;
  }

  @keyframes show {
    0%, 49.99% {
      opacity: 0;
      z-index: 1;
    }
    50%, 100% {
      opacity: 1;
      z-index: 5;
    }
  }

  .overlay-container {
    position: absolute;
    top: 0;
    left: 50%;
    width: 50%;
    height: 100%;
    overflow: hidden;
    transition: transform 0.6s ease-in-out;
    z-index: 100;
  }

  .container.right-panel-active .overlay-container {
    transform: translateX(-100%);
  }

  .overlay {
    background: linear-gradient(to right, #193857, #001F3F); /* Secondary → Primary gradient */
    background-repeat: no-repeat;
    background-size: cover;
    background-position: 0 0;
    color: #FFFFFF;
    position: relative;
    left: -100%;
    height: 100%;
    width: 200%;
    transform: translateX(0);
    transition: transform 0.6s ease-in-out;
  }

  .container.right-panel-active .overlay {
    transform: translateX(50%);
  }

  .overlay-panel {
    position: absolute;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-direction: column;
    padding: 0 40px;
    text-align: center;
    top: 0;
    height: 100%;
    width: 50%;
    transform: translateX(0);
    transition: transform 0.6s ease-in-out;
    color: #FFFFFF; /* Clean White */
  }

  .overlay-left {
    transform: translateX(-20%);
  }

  .container.right-panel-active .overlay-left {
    transform: translateX(0);
  }

  .overlay-right {
    right: 0;
    transform: translateX(0);
  }

  .container.right-panel-active .overlay-right {
    transform: translateX(20%);
  }
@media (max-width: 768px) {
    .container {
      width: 95%;
      min-height: 600px;
    }
    .overlay-container {
      display: none;
    }
    .login-container,
    .register-container {
      width: 100%;
      position: relative;
      opacity: 1 !important;
    }
  }
</style>

  </head>
  <body>
    <div class="container" id="container">
      <div class="form-container login-container">
        <form action="#">
          <h1>Login</h1>
          <div class="social-container">
            <a href="#" class="social">F</a>
            <a href="#" class="social">G+</a>
            <a href="#" class="social">In</a>
          </div>
          <span>or use your account</span>
          <input type="email" placeholder="Email" />
          <input type="password" placeholder="Password" />
          <a href="#">Forgot your password?</a>
          <button>Login</button>
        </form>
      </div><div class="form-container register-container">
    <form action="#">
      <h1>Create Account</h1>
      <div class="social-container">
        <a href="#" class="social">F</a>
        <a href="#" class="social">G+</a>
        <a href="#" class="social">In</a>
      </div>
      <span>or use your email for registration</span>

      <input type="text" placeholder="Name" />
      <input type="number" placeholder="Age" />
      <input type="text" placeholder="Contact" />
      <input type="text" placeholder="Address" />
      <input type="email" placeholder="Email" />
      <input type="password" placeholder="Password" />
      <input type="password" placeholder="Confirm Password" />
      <input type="date" placeholder="DOB" />

      <button>Register</button>
    </form>
  </div>
  <div class="overlay-container">
    <div class="overlay">
      <div class="overlay-panel overlay-left">
        <h1>Welcome to Renture!</h1>
        <p>To keep connected with us please login with your personal info</p>
        <button class="ghost" id="login">Login</button>
      </div>
      <div class="overlay-panel overlay-right">
        <h1>Hello, Friend!</h1>
        <p>Enter your personal details and start your journey with us</p>
        <button class="ghost" id="register">Register</button>
      </div>
    </div>
  </div>
</div>

<script>
  const registerButton = document.getElementById('register');
  const loginButton = document.getElementById('login');
  const container = document.getElementById('container');

  registerButton.addEventListener('click', () => {
    container.classList.add('right-panel-active');
  });

  loginButton.addEventListener('click', () => {
    container.classList.remove('right-panel-active');
  });
</script>

  <script>
  document.querySelector('.register-container form').addEventListener('submit', function(e) {
    const age = parseInt(document.querySelector('input[placeholder="Age"]').value);
    const contact = document.querySelector('input[placeholder="Contact"]').value;
    const email = document.querySelector('input[placeholder="Email"]').value;
    const password = document.querySelector('input[placeholder="Password"]').value;
    const confirmPassword = document.querySelector('input[placeholder="Confirm Password"]').value;
    const dob = document.querySelector('input[placeholder="DOB"]').value;

    if (isNaN(age) || age < 1 || age > 120) {
      alert('Please enter a valid age.');
      e.preventDefault();
      return;
    }

    if (contact.length !== 10 || !/^[0-9]+$/.test(contact)) {
      alert('Contact must be a 10-digit number.');
      e.preventDefault();
      return;
    }

    if (!email.includes('@') || !email.includes('.')) {
      alert('Please enter a valid email.');
      e.preventDefault();
      return;
    }

    if (password.length < 6) {
      alert('Password must be at least 6 characters.');
      e.preventDefault();
      return;
    }

    if (password !== confirmPassword) {
      alert('Passwords do not match.');
      e.preventDefault();
      return;
    }

    if (!dob) {
      alert('Please select your Date of Birth.');
      e.preventDefault();
      return;
    }
  });
</script></body>
</html>