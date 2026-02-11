<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

$mysqli = new mysqli("localhost", "root", "", "renture");
if ($mysqli->connect_error) {
    die("DB connect error: " . $mysqli->connect_error);
}
    function alert_and_back($msg) {
        echo "<script>";
        echo "alert(" . json_encode($msg) . ");";
        echo "window.history.back();";
        echo "</script>";
        exit();
    }

/* ------------------ REGISTRATION ------------------ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['register'])) {

    $Name     = trim($_POST['Name'] ?? '');
    $Age      = trim($_POST['Age'] ?? '');
    $Contact  = trim($_POST['Contact'] ?? '');
    $Address  = trim($_POST['Address'] ?? '');
    $Email    = trim($_POST['Email'] ?? '');
    $Password = $_POST['Password'] ?? '';
    $Confirm  = $_POST['Confirm_Password'] ?? '';
    $DOB      = trim($_POST['DOB'] ?? '');

    if (!$Name || !$Age || !$Contact || !$Address || !$Email || !$Password || !$Confirm || !$DOB) {
        alert_and_back('Please fill all fields.');
    }

    if ($Password !== $Confirm) {
        alert_and_back('Passwords do not match.');
    }

    $stmt = $mysqli->prepare("SELECT id FROM users WHERE Email = ?");
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        $stmt->close();
        alert_and_back('Email already registered.');
    }
    $stmt->close();

    $Hash = password_hash($Password, PASSWORD_DEFAULT);

    $stmt = $mysqli->prepare("INSERT INTO users (Name, Age, Contact, Address, Email, Password, DOB) 
                              VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sisssss", $Name, $Age, $Contact, $Address, $Email, $Hash, $DOB);

    if ($stmt->execute()) {
        echo "<script>alert('Registration successful!'); window.location.href='log_reg.html';</script>";
        exit();
    } else {
        alert_and_back('Registration failed.');
    }
}

/* ------------------ LOGIN ------------------ */
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {

    // MATCH HTML EXACTLY
    $Email = trim($_POST['Email'] ?? '');
    $Password = $_POST['Password'] ?? '';

    if (!$Email || !$Password) {
        alert_and_back("Enter Email and Password");
    }

    $stmt = $mysqli->prepare("SELECT id, Name, Password FROM users WHERE Email = ?");
    $stmt->bind_param("s", $Email);
    $stmt->execute();
    $res = $stmt->get_result();
    $user = $res->fetch_assoc();

    if ($user && password_verify($Password, $user['Password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_name'] = $user['Name'];

        echo "<script>alert('Login successful!'); window.location.href='index.html';</script>";
        exit();
    } else {
        alert_and_back('Invalid email or password.');
    }
}

?>
