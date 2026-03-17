<?php
session_start();
include '../database/db_config.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // --- REGISTRATION LOGIC ---
    if (isset($_POST['register'])) {
        $name     = $_POST['Name'];
        $age      = $_POST['Age'];
        $contact  = $_POST['Contact'];
        $address  = $_POST['Address'];
        $email    = $_POST['Email'];
        $dob      = $_POST['DOB'];
        $password = password_hash($_POST['Password'], PASSWORD_BCRYPT);

        try {
            $sql = "INSERT INTO users (name, email, password, age, contact, address, dob) VALUES (?, ?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssisss", $name, $email, $password, $age, $contact, $address, $dob);

            if ($stmt->execute()) {
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
            if (password_verify($pass, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['name'];
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
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renture — Black & White</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --pure-white: #F8FAFC;
            --off-white: #F8FAFC;
            --deep-black: #1E293B;
            --soft-black: #1E293B;
            --border: #E2E8F0;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            background-color: #F8FAFC;
            background-image:
                radial-gradient(ellipse at 20% 20%, rgba(30,41,59,0.06) 0%, transparent 55%),
                radial-gradient(ellipse at 80% 80%, rgba(30,41,59,0.04) 0%, transparent 55%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            font-family: 'DM Sans', sans-serif;
            overflow: hidden;
        }

        body::before, body::after {
            content: '';
            position: fixed;
            border-radius: 50%;
            filter: blur(90px);
            pointer-events: none;
            z-index: 0;
        }
        body::before {
            width: 450px; height: 450px;
            background: rgba(226,232,240,0.3);
            top: -120px; left: -120px;
        }
        body::after {
            width: 380px; height: 380px;
            background: rgba(226,232,240,0.2);
            bottom: -100px; right: -100px;
        }

        .container {
            background: rgba(226, 232, 240, 0.75);
            backdrop-filter: blur(30px) saturate(180%);
            -webkit-backdrop-filter: blur(30px) saturate(180%);
            border: 1px solid rgba(248, 250, 252, 0.6);
            border-radius: 26px;
            box-shadow:
                0 50px 100px rgba(30, 41, 59, 0.25),
                0 0 0 1px rgba(248,250,252,0.3) inset,
                0 1px 0 rgba(248,250,252,0.9) inset;
            position: relative;
            width: 780px;
            max-width: 95%;
            height: 580px;
            overflow: hidden;
            z-index: 1;
        }

        .container::before {
            content: '';
            position: absolute;
            top: 0; left: 8%; right: 8%;
            height: 1px;
            background: linear-gradient(90deg, transparent, rgba(248,250,252,1), transparent);
            z-index: 200;
        }

        .form-container {
            position: absolute;
            top: 0;
            height: 100%;
            transition: all 0.6s cubic-bezier(0.7, 0, 0.3, 1);
        }

        .login-container { left: 0; width: 50%; z-index: 2; opacity: 1; }
        .register-container { left: 0; width: 50%; opacity: 0; z-index: 1; }

        .container.right-panel-active .login-container { transform: translateX(100%); opacity: 0; z-index: 1; }
        .container.right-panel-active .register-container { transform: translateX(100%); opacity: 1; z-index: 5; }

        form {
            background: transparent;
            display: flex;
            flex-direction: column;
            padding: 32px 40px;
            height: 100%;
            align-items: center;
            text-align: center;
        }

        .login-container form {
            justify-content: center;
            gap: 8px;
        }

        .register-container form {
            justify-content: center;
            gap: 7px;
            padding: 24px 40px;
        }

        h1 {
            font-family: 'DM Serif Display', serif;
            color: #1E293B;
            font-size: 1.9rem;
            margin-bottom: 2px;
            letter-spacing: -0.5px;
        }

        p { color: #475569; font-size: 12.5px; margin-bottom: 4px; }

        .input-row { display: flex; gap: 8px; width: 100%; }

        input,
        input[type="text"],
        input[type="email"],
        input[type="password"],
        input[type="number"],
        input[type="tel"],
        input[type="date"] {
            background: rgba(248, 250, 252, 0.75);
            border: 1px solid rgba(226, 232, 240, 0.9);
            color: #1E293B !important;
            -webkit-text-fill-color: #1E293B !important;
            padding: 10px 14px;
            width: 100%;
            border-radius: 12px;
            outline: none;
            font-size: 12.5px;
            font-family: 'DM Sans', sans-serif;
            transition: all 0.3s ease;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            box-shadow:
                0 2px 8px rgba(30,41,59,0.08),
                inset 0 1px 0 rgba(248,250,252,0.9),
                inset 0 -1px 0 rgba(30,41,59,0.04);
            color-scheme: light;
        }

        input::placeholder { color: rgba(30,41,59,0.4) !important; }

        input:focus {
            border-color: rgba(250,204,21,0.5);
            background: rgba(248, 250, 252, 0.95);
            box-shadow:
                0 0 0 3px rgba(250,204,21,0.12),
                0 2px 12px rgba(30,41,59,0.1),
                inset 0 1px 0 rgba(248,250,252,1);
            color: #1E293B !important;
            -webkit-text-fill-color: #1E293B !important;
        }

        input.placeholder-light::placeholder {
            color: rgba(30,41,59,0.18) !important;
            -webkit-text-fill-color: rgba(30,41,59,0.18) !important;
        }

        input[type="date"] {
            color: #1E293B !important;
            -webkit-text-fill-color: #1E293B !important;
            color-scheme: light;
        }
        input[type="date"]::-webkit-calendar-picker-indicator {
            filter: invert(0);
            opacity: 0.5;
            cursor: pointer;
        }
        input[type="date"]::-webkit-datetime-edit { color: #1E293B !important; -webkit-text-fill-color: #1E293B !important; }
        input[type="date"]::-webkit-datetime-edit-fields-wrapper { color: #1E293B !important; -webkit-text-fill-color: #1E293B !important; }
        input[type="date"]::-webkit-datetime-edit-text { color: #1E293B !important; -webkit-text-fill-color: #1E293B !important; }
        input[type="date"]::-webkit-datetime-edit-day-field { color: #1E293B !important; -webkit-text-fill-color: #1E293B !important; }
        input[type="date"]::-webkit-datetime-edit-month-field { color: #1E293B !important; -webkit-text-fill-color: #1E293B !important; }
        input[type="date"]::-webkit-datetime-edit-year-field { color: #1E293B !important; -webkit-text-fill-color: #1E293B !important; }

        input[type="date"].placeholder-light::-webkit-datetime-edit-day-field,
        input[type="date"].placeholder-light::-webkit-datetime-edit-month-field,
        input[type="date"].placeholder-light::-webkit-datetime-edit-year-field,
        input[type="date"].placeholder-light::-webkit-datetime-edit-text {
            color: rgba(30,41,59,0.2) !important;
            -webkit-text-fill-color: rgba(30,41,59,0.2) !important;
        }

        input[type="number"]::-webkit-inner-spin-button,
        input[type="number"]::-webkit-outer-spin-button { -webkit-appearance: none; margin: 0; }
        input[type="number"] { -moz-appearance: textfield; }

        button {
            border-radius: 50px;
            background: #FACC15;
            color: #475569;
            font-size: 10.5px;
            font-weight: 700;
            font-family: 'DM Sans', sans-serif;
            padding: 12px 44px;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            cursor: pointer;
            border: 2px solid #FACC15;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(250,204,21,0.35);
            width: 100%;
        }

        button:hover {
            background: transparent;
            color: #475569;
            border-color: #475569;
            transform: translateY(-1px);
            box-shadow: 0 8px 25px rgba(30,41,59,0.15);
        }

        .overlay-container {
            position: absolute;
            top: 0; left: 50%;
            width: 50%; height: 100%;
            overflow: hidden;
            transition: transform 0.6s cubic-bezier(0.7, 0, 0.3, 1);
            z-index: 100;
        }

        .container.right-panel-active .overlay-container { transform: translateX(-100%); }

        .overlay {
            background: linear-gradient(135deg, rgba(30,41,59,0.92) 0%, rgba(15,23,42,0.95) 50%, rgba(30,41,59,0.92) 100%);
            backdrop-filter: blur(50px) saturate(180%) brightness(0.9);
            -webkit-backdrop-filter: blur(50px) saturate(180%) brightness(0.9);
            color: #F8FAFC;
            position: relative;
            left: -100%; height: 100%; width: 200%;
            transform: translateX(0);
            transition: transform 0.6s cubic-bezier(0.7, 0, 0.3, 1);
            border-left: 1px solid rgba(226,232,240,0.15);
            box-shadow: inset 1px 0 0 rgba(248,250,252,0.1), -20px 0 60px rgba(30,41,59,0.3);
        }

        .overlay::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(248,250,252,0.4), transparent);
            z-index: 1;
        }

        .overlay::after {
            content: "";
            position: absolute;
            inset: 0;
            background:
                radial-gradient(circle at 50% 0%, rgba(248,250,252,0.08) 0%, transparent 55%),
                radial-gradient(circle at 50% 100%, rgba(248,250,252,0.03) 0%, transparent 45%);
            pointer-events: none;
        }

        .container.right-panel-active .overlay { transform: translateX(50%); }

        .overlay-panel {
            position: absolute;
            display: flex; align-items: center; justify-content: center; flex-direction: column;
            padding: 0 34px; text-align: center;
            top: 0; height: 100%; width: 50%; gap: 12px;
        }

        .overlay h1 { color: #F8FAFC; text-shadow: 0 2px 20px rgba(248,250,252,0.2); }
        .overlay p { color: rgba(226,232,240,0.75); line-height: 1.6; }

        button.ghost {
            background: rgba(248,250,252,0.08);
            border: 1.5px solid rgba(248,250,252,0.6);
            color: #F8FAFC;
            backdrop-filter: blur(10px);
            box-shadow: inset 0 1px 0 rgba(248,250,252,0.15), 0 0 20px rgba(248,250,252,0.05);
            width: auto;
        }

        button.ghost:hover {
            background: rgba(248,250,252,0.18);
            border-color: #F8FAFC;
            color: #F8FAFC;
            box-shadow: 0 0 30px rgba(248,250,252,0.15), inset 0 1px 0 rgba(248,250,252,0.2);
            transform: translateY(-1px);
        }

        .overlay-left { transform: translateX(-20%); }
        .container.right-panel-active .overlay-left { transform: translateX(0); }
        .overlay-right { right: 0; transform: translateX(0); }
        .container.right-panel-active .overlay-right { transform: translateX(20%); }

        .divider {
            display: flex; align-items: center;
            width: 100%; color: #94A3B8; font-size: 10px; letter-spacing: 1px; margin: 4px 0;
        }
        .divider::after, .divider::before {
            content: ""; flex: 1; height: 1px;
            background: linear-gradient(90deg, transparent, rgba(30,41,59,0.1), transparent);
            margin: 0 10px;
        }

        .social-container a {
            border: 1px solid rgba(30,41,59,0.15);
            border-radius: 50%;
            display: inline-flex; justify-content: center; align-items: center;
            margin: 0 4px; height: 34px; width: 34px;
            color: #1E293B; text-decoration: none;
            font-size: 12px; font-weight: 600;
            transition: all 0.3s ease;
            background: rgba(248,250,252,0.7);
            backdrop-filter: blur(10px);
            box-shadow: inset 0 1px 0 rgba(248,250,252,0.8), 0 2px 8px rgba(30,41,59,0.08);
        }

        .social-container a:hover {
            background: #FACC15; border-color: #FACC15; color: #475569;
            transform: translateY(-2px); box-shadow: 0 6px 20px rgba(250,204,21,0.3);
        }

        .error-msg {
            color: #ef4444;
            font-size: 11px;
            margin-top: -4px;
            display: none;
            width: 100%;
            text-align: left;
            padding-left: 4px;
        }
    </style>
</head>
<body>

<?php if ($message != ""): ?>
    <script>alert("<?php echo $message; ?>");</script>
<?php endif; ?>

<div class="container" id="container">

    <div class="form-container login-container">
        <form action="" method="post" id="loginForm">
            <h1>Welcome Back</h1>
            <p>Login to your account</p>
            <input type="email" name="Email" placeholder="Email address" required />
            <input type="password" name="Password" placeholder="Password" required />
            <button name="login" type="submit">Sign In</button>
            <div class="divider">or</div>
            <div class="social-container">
                <a href="#">F</a><a href="#">G+</a><a href="#">in</a>
            </div>
        </form>
    </div>

    <div class="form-container register-container">
        <form action="" method="post" id="regForm">
            <h1>Create Account</h1>
            <p>Join the community</p>
            <input type="text" name="Name" placeholder="Full Name" required />
            <div class="input-row">
                <input type="number" name="Age" placeholder="Age" min="1" max="100" required />
                <input type="tel" name="Contact" placeholder="Contact" pattern="[0-9]{10}" required />
            </div>
            <input type="text" name="Address" placeholder="Address" required />
            <input type="email" name="Email" placeholder="Email" required />
            <input type="password" name="Password" id="regPass" placeholder="Password" required minlength="6" />
            <input type="password" name="ConfirmPassword" id="confirmPass" placeholder="Confirm Password" required />
            <div id="passError" class="error-msg">Passwords do not match!</div>
            <input type="date" name="DOB" required />
            <button name="register" type="submit">Create Account</button>
        </form>
    </div>

    <div class="overlay-container">
        <div class="overlay">
            <div class="overlay-panel overlay-left">
                <h1>Hello!</h1>
                <p>Already have an account? Log in here.</p>
                <button class="ghost" id="signInBtn">Sign In</button>
            </div>
            <div class="overlay-panel overlay-right">
                <h1>Welcome!</h1>
                <p>Start your journey with us today.</p>
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

    // Password match validation
    const regForm = document.getElementById('regForm');
    const regPass = document.getElementById('regPass');
    const confirmPass = document.getElementById('confirmPass');
    const passError = document.getElementById('passError');

    confirmPass.addEventListener('input', () => {
        if (confirmPass.value && regPass.value !== confirmPass.value) {
            passError.style.display = 'block';
            confirmPass.style.borderColor = 'rgba(239,68,68,0.6)';
        } else {
            passError.style.display = 'none';
            confirmPass.style.borderColor = '';
        }
    });

    regForm.onsubmit = function(e) {
        if (regPass.value !== confirmPass.value) {
            passError.style.display = 'block';
            confirmPass.style.borderColor = 'rgba(239,68,68,0.6)';
            e.preventDefault();
            return false;
        }
    };

    // Placeholder focus behavior
    document.querySelectorAll('input').forEach(input => {
        const original = input.getAttribute('placeholder') || '';

        input.addEventListener('focus', () => {
            input.setAttribute('placeholder', '');
            input.classList.add('placeholder-light');
        });

        input.addEventListener('blur', () => {
            input.setAttribute('placeholder', original);
            input.classList.remove('placeholder-light');
        });
    });
</script>

</body>
</html>