<!-- <?php
// session_start();
// require 'config.php';

// if ($_SERVER["REQUEST_METHOD"] == "POST") {
//     $username = trim($_POST['username'] ?? '');
//     $password = $_POST['password'] ?? '';

//     if (empty($username) || empty($password)) {
//         echo 'invalid';
//         exit;
//     }

//     try {
//         $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
//         $stmt->execute([$username]);
//         $user = $stmt->fetch();

//         if ($user && password_verify($password, $user['password'])) {
//             session_regenerate_id(true);
//             $_SESSION['user_id'] = $user['id'];
//             $_SESSION['username'] = $user['username'];
//             echo 'success';
//         } else {
//             echo 'invalid';
//         }
//     } catch (PDOException $e) {
//         error_log("Database error: " . $e->getMessage());
//         echo 'invalid';
//     }
//     exit;
// }

// If user already logged in, redirect to dashboard
// if (isset($_SESSION['user_id'])) {
//     header('Location: dashboard.php');
//     exit;
// }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Login - BEDRIVE</title>
    <style>
        /* Your existing styles for login box */
        body {
            background: #1e1e1e;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        #loginBox {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background: #2b2b2b;
            border-radius: 8px;
            box-shadow: 0 0 10px #000;
        }
        input, button {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
        button {
            background: #4c8bf5;
            border: none;
            color: white;
            cursor: pointer;
            border-radius: 5px;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="loginBox">
        <h2>Login</h2>
        <input type="text" id="username" placeholder="Username" autocomplete="username" /><br />
        <input type="password" id="password" placeholder="Password" autocomplete="current-password" /><br />
        <button id="loginBtn">Login</button>
        <p id="loginStatus"></p>
    </div>

    <script>
        $(document).ready(function () {
            $("#loginBtn").click(function (event) {
                event.preventDefault();
                const username = $("#username").val();
                const password = $("#password").val();
                $.post("", { username, password }, function (res) {
                    if (res.trim() === "success") {
                        window.location.href = "dashboard.php";
                    } else {
                        $("#loginStatus").text("Invalid login!");
                    }
                });
            });
        });
    </script>
</body>
</html> -->


<?php
session_start();
require 'config.php'; // This should contain your DB connection ($pdo)

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($username === '' || $password === '') {
        $error = 'Please fill in both fields.';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header("Location: dashboard.php");
                exit;
            } else {
                $error = 'Invalid username or password.';
            }
        } catch (PDOException $e) {
            error_log("Login error: " . $e->getMessage());
            $error = 'Internal server error.';
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - BEDRIVE</title>
  <style>
    body {
      background: linear-gradient(to right, #1e1e1e, #2b2b2b);
      font-family: 'Segoe UI', sans-serif;
      color: white;
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    }

    .login-box {
      background: #2b2b2b;
      padding: 40px;
      border-radius: 10px;
      box-shadow: 0 4px 20px rgba(0,0,0,0.5);
      width: 100%;
      max-width: 400px;
    }

    .login-box h2 {
      text-align: center;
      color: #4c8bf5;
      margin-bottom: 25px;
    }

    .login-box input {
      width: 100%;
      padding: 12px;
      margin: 10px 0;
      border: none;
      border-radius: 6px;
      background: #1e1e1e;
      color: white;
    }

    .login-box button {
      width: 100%;
      padding: 12px;
      background: #4c8bf5;
      color: white;
      border: none;
      border-radius: 6px;
      cursor: pointer;
      margin-top: 10px;
      font-size: 1em;
    }

    .login-box button:hover {
      background: #3a6edc;
    }

    .error {
      color: red;
      margin-top: 10px;
      text-align: center;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 15px;
      color: #aaa;
      text-decoration: none;
      font-size: 0.9em;
    }

    .back-link:hover {
      color: #4c8bf5;
    }
  </style>
</head>
<body>

  <div class="login-box">
    <h2>Login to BEDRIVE</h2>
    <form method="POST">
      <input type="text" name="username" placeholder="Username" required />
      <input type="password" name="password" placeholder="Password" required />
      <button type="submit">Login</button>
      <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
      <?php endif; ?>
    </form>
    <a href="home.php" class="back-link">← Back to Home</a>
  </div>

</body>
</html>
