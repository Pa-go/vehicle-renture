<?php
session_start();
// Step 1: Link to database (climbing out of 'pages' folder)
include '../database/db_config.php'; 

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- REGISTRATION LOGIC ---
    if (isset($_POST['register'])) {
        // Matching Capitalized names from your HTML
        $name     = $_POST['Name'];
        $age      = $_POST['Age'];
        $contact  = $_POST['Contact'];
        $address  = $_POST['Address'];
        $email    = $_POST['Email'];
        $dob      = $_POST['DOB'];
        // Hash the password for security
        $password = password_hash($_POST['Password'], PASSWORD_BCRYPT);

        try {
            $sql = "INSERT INTO users (name, email, password, age, contact, address, dob) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssisss", $name, $email, $password, $age, $contact, $address, $dob);
            
            if ($stmt->execute()) {
                // Success: Alert and stay on page to allow login
                echo "<script>alert('Registration Successful! You can now Login.');</script>";
            }
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                $message = "Error: This email is already registered.";
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }

    // --- LOGIN LOGIC ---
    if (isset($_POST['login'])) {
        $email = $_POST['Email'];
        $pass  = $_POST['Password'];

        $sql = "SELECT id, name, password FROM users WHERE email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // Verify the hashed password
            if (password_verify($pass, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
                
                // Redirecting out of 'pages' to root home.php
                header("Location: ../pages/home.php");
                exit();
            } else {
                $message = "Invalid Password!";
            }
        } else {
            $message = "No account found with this email.";
        }
    }
}
?>

<!DOCTYPE html>
<html>
<head>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;600&display=swap" rel="stylesheet">
<style>
/* Global Reset */
* { box-sizing: border-box; }

body {
  background-color: #E6F0F4;
  margin: 0;
  font-family: 'Montserrat', sans-serif;
  display: flex;
  justify-content: center;
  align-items: center;
  min-height: 100vh;
}

h1 { font-weight: bold; margin: 0; color: #001F3F; }

p {
  font-size: 14px;
  font-weight: 300;
  line-height: 20px;
  letter-spacing: 0.5px;
  margin: 15px 0 20px;
}

button {
  border-radius: 20px;
  border: 1px solid #FFD700;
  background-color: #FFD700;
  color: #001F3F;
  font-size: 12px;
  font-weight: bold;
  padding: 12px 45px;
  letter-spacing: 1px;
  text-transform: uppercase;
  transition: transform 80ms ease-in;
  cursor: pointer;
  margin-top: 10px;
}

button:active { transform: scale(0.95); }
button.ghost { background-color: transparent; border-color: #FFFFFF; color: #FFFFFF; }

form {
  background-color: #FFFFFF;
  display: flex;
  flex-direction: column;
  padding: 20px 40px;
  height: 100%;
  justify-content: center;
  align-items: center;
  text-align: center;
  gap: 8px;
}

input {
  background-color: #EEE;
  border: 1px solid #FFD700;
  padding: 10px 15px;
  width: 100%;
  border-radius: 8px;
  font-size: 13px;
}

.social-container { margin: 10px 0; }
.social-container a {
  border: 1px solid #193857;
  border-radius: 50%;
  display: inline-flex;
  justify-content: center;
  align-items: center;
  margin: 0 5px;
  height: 35px;
  width: 35px;
  color: #193857;
  text-decoration: none;
}

.container {
  background-color: #FFFFFF;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
  position: relative;
  overflow: hidden;
  width: 850px;
  max-width: 95%;
  min-height: 600px;
}

.form-container {
  position: absolute;
  top: 0;
  height: 100%;
  transition: all 0.6s ease-in-out;
}

.login-container { left: 0; width: 50%; z-index: 2; }
.register-container { left: 0; width: 50%; opacity: 0; z-index: 1; }

.container.right-panel-active .login-container { transform: translateX(100%); }
.container.right-panel-active .register-container {
  transform: translateX(100%);
  opacity: 1;
  z-index: 5;
  animation: show 0.6s;
}

@keyframes show {
  0%, 49.99% { opacity: 0; z-index: 1; }
  50%, 100% { opacity: 1; z-index: 5; }
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

.container.right-panel-active .overlay-container { transform: translateX(-100%); }

.overlay {
  background: linear-gradient(to right, #193857, #001F3F);
  color: #FFFFFF;
  position: relative;
  left: -100%;
  height: 100%;
  width: 200%;
  transform: translateX(0);
  transition: transform 0.6s ease-in-out;
}

.container.right-panel-active .overlay { transform: translateX(50%); }

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
  transition: transform 0.6s ease-in-out;
}

.overlay-left { transform: translateX(-20%);  color:#FFFFFF;}
.container.right-panel-active .overlay-left { transform: translateX(0); }
.overlay-right { right: 0; transform: translateX(0); }
.container.right-panel-active .overlay-right { transform: translateX(20%); }

button:hover { background-color: #001F3F; color: #FFD700; border-color: #001F3F; }
button.ghost:hover { background-color: #FFFFFF; color: #001F3F; }

.error-msg {
  color: #ff4d4d;
  font-size: 11px;
  margin-top: -5px;
  display: none;
}
</style>
</head>
<body>

<?php if($message != ""): ?>
    <script>alert("<?php echo $message; ?>");</script>
<?php endif; ?>

<div class="container" id="container">

  <div class="form-container login-container">
    <form action="" method="post" id="loginForm">
      <h1>Login</h1>
      <input type="email" name="Email" placeholder="Email" required />
      <input type="password" name="Password" placeholder="Password" required />
      <a href="#" style="font-size: 11px; text-decoration: none; color: #001F3F; align-self: flex-start;">Forgot your password?</a>
      <button name="login" type="submit">Login</button>
      <div class="social-container">
        <a href="#">F</a><a href="#">G+</a><a href="#">In</a>
      </div>
    </form>
  </div>

  <div class="form-container register-container">
    <form action="" method="post" id="regForm">
      <h1 style="margin-bottom: 10px;">Create Account</h1>
      <input type="text" name="Name" placeholder="Name" required />
      <input type="number" name="Age" placeholder="Age" max="100" min="1" required />
      <input type="tel" name="Contact" id="contact" placeholder="Contact (10 digits)" pattern="[0-9]{10}" required />
      <input type="text" name="Address" placeholder="Address" required /> 
      <input type="email" name="Email" placeholder="Email" required />
      <input type="password" name="Password" id="regPass" placeholder="Password" required minlength="6" />
      <input type="password" name="ConfirmPassword" id="confirmPass" placeholder="Confirm Password" required />
      <div id="passError" class="error-msg">Passwords do not match!</div>
      <input type="date" name="DOB" required />
      <button name="register" type="submit">Register</button>
      <div class="social-container">
        <a href="#">F</a><a href="#">G+</a><a href="#">In</a>
      </div>
    </form>
  </div>

  <div class="overlay-container">
    <div class="overlay">
      <div class="overlay-panel overlay-left">
        <h1 style="color: #FFFFFF;">Join Renture!</h1>
        <p>Create your account Now!!</p>
        <button class="ghost" id="signInBtn">Login</button>
      </div>
      <div class="overlay-panel overlay-right">
        <h1 style="color: #FFFFFF;">Hello, Friend!</h1>
        <p>Enter your personal details and start your journey with us</p>
        <button class="ghost" id="signUpBtn">Register</button>
      </div>
    </div>
  </div>

</div>

<script>
const signUpBtn = document.getElementById('signUpBtn');
const signInBtn = document.getElementById('signInBtn');
const container = document.getElementById('container');

signUpBtn.addEventListener('click', () => container.classList.add('right-panel-active'));
signInBtn.addEventListener('click', () => container.classList.remove('right-panel-active'));

// Validation Logic
const regForm = document.getElementById('regForm');
const password = document.getElementById('regPass');
const confirmPassword = document.getElementById('confirmPass');
const passError = document.getElementById('passError');

regForm.onsubmit = function(e) {
  if (password.value !== confirmPassword.value) {
    passError.style.display = 'block';
    confirmPassword.style.border = '1px solid #ff4d4d';
    e.preventDefault(); 
    return false;
  }
};
</script>

</body>
</html>