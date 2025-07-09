<?php
require 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (!$username || !$password || !$confirm_password) {
        echo 'Please fill out all fields.';
        exit;
    }

    if ($password !== $confirm_password) {
        echo 'Passwords do not match.';
        exit;
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ?");
    $stmt->execute([$username]);

    if ($stmt->fetchColumn() > 0) {
        echo 'Username already exists.';
        exit;
    }

    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
    $stmt->execute([$username, $hashed_password]);

    echo 'Registration successful!';
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <style>
        body {
            background: #1e1e1e;
            color: white;
            font-family: Arial, sans-serif;
            padding: 20px;
        }

        #registerBox {
            max-width: 400px;
            margin: auto;
            padding: 20px;
            background: #2b2b2b;
            border-radius: 8px;
            box-shadow: 0 0 10px #000;
        }

        input, button {
            width: 90%;
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
        }

        button {
            background: #4c8bf5;
            border: none;
            color: white;
            cursor: pointer;
        }

        #registerStatus {
            margin-top: 10px;
            color: #ff6961;
        }
    </style>
    <!-- Include jQuery once -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div id="registerBox">
        <h2>Register</h2>
        <input type="text" id="username" placeholder="Username" /><br />
        <input type="password" id="password" placeholder="Password" /><br />
        <input type="password" id="confirm_password" placeholder="Confirm Password" /><br />
        <button id="registerBtn" type="button">Register</button>
        <p id="registerStatus"></p>
    </div>

    <script>
    $(document).ready(function () {
        $("#registerBtn").on("click", function (event) {
            event.preventDefault();

            const username = $("#username").val().trim();
            const password = $("#password").val();
            const confirm_password = $("#confirm_password").val();

            $.post("", {
                username,
                password,
                confirm_password
            }, function (res) {
                $("#registerStatus").text(res);
                if (res.trim() === 'Registration successful!') {
                    $("#registerStatus").css("color", "lightgreen");
                    setTimeout(() => window.location.href = 'login.php', 1500);
                }
            });
        });
    });
    </script>
</body>
</html>
