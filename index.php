<?php
// index.php - Splash entry page so Apache serves this first (then redirects to index.html)
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Renture | Loading...</title>
  <!-- Fallback meta refresh for environments where JS is disabled -->
  <meta http-equiv="refresh" content="3.5;url=home.php">
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { height: 100vh; display: flex; justify-content: center; align-items: center; background: #001F3F; background-size: 400% 400%; animation: gradientMove 10s ease infinite; overflow: hidden; }
    @keyframes gradientMove { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    .logo { width: 350px; max-width: 80%; animation: floatLogo 3.5s ease-in-out infinite, glowEffect 2.5s ease-in-out infinite; filter: drop-shadow(0 0 20px rgba(255,255,255,0.3)); }
    @keyframes floatLogo { 0% { transform: translateY(0) scale(1); } 50% { transform: translateY(-15px) scale(1.08); } 100% { transform: translateY(0) scale(1); } }
    @keyframes glowEffect { 0%,100%{ filter: drop-shadow(0 0 15px rgba(123,51,126,0.6)) drop-shadow(0 0 30px rgba(102,103,171,0.5)); } 50%{ filter: drop-shadow(0 0 35px rgba(245,213,224,0.8)) drop-shadow(0 0 50px rgba(102,103,171,0.8)); } }
  </style>
</head>
<body>
  <img src="images/logo.png" alt="Renture Logo" class="logo" />

  <script>
    // After 3.5s navigate to the main page. Use replace so splash isn't kept in history.
    setTimeout(function () {
      try {
        window.location.replace('home.php');
      } catch (e) {
        // as a fallback, set href
        window.location.href = 'home.php';
      }
    }, 3500);
  </script>
</body>
</html>
