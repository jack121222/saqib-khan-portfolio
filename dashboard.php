

<!-- login.php -->
<?php
session_start();
$valid_users = ['admin' => 'admin123']; // Replace with database logic

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = $_POST['username'];
    $pass = $_POST['password'];

    if (isset($valid_users[$user]) && $valid_users[$user] === $pass) {
        $_SESSION['user'] = $user;
        header("Location: dashboard.php");
    } else {
        echo "<script>alert('Invalid login!'); window.location='hme.php';</script>";
    }
}
?>

<!-- dashboard.php -->
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: home.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>File Manager</title>
    <style>
        body { background: #f5f5f5; font-family: Arial; margin: 0; }
        header { background: #1976d2; padding: 15px; color: white; display: flex; justify-content: space-between; }
        #sidebar { width: 200px; background: #eeeeee; position: fixed; top: 60px; bottom: 0; padding: 10px; }
        #main { margin-left: 220px; padding: 20px; }
        .file-box { display: inline-block; width: 120px; height: 120px; margin: 10px; text-align: center; background: white; border: 1px solid #ccc; border-radius: 6px; padding: 10px; }
        input[type="file"] { display: none; }
        label.upload-btn { display: inline-block; padding: 8px 12px; background: #1976d2; color: white; cursor: pointer; border-radius: 5px; }
    </style>
</head>
<body>
    <header>
        <div><strong>MyDrive</strong></div>
        <div>
            Logged in as: <?= $_SESSION['user'] ?> |
            <a href="logout.php" style="color: white">Logout</a>
        </div>
    </header>

    <div id="sidebar">
        <p><strong>Folders</strong></p>
        <p>All Files</p>
        <p>Shared</p>
        <p>Trash</p>
    </div>

    <div id="main">
        <h2>Your Files</h2>
        <form method="POST" action="upload.php" enctype="multipart/form-data">
            <label class="upload-btn">
                Upload File
                <input type="file" name="file" onchange="this.form.submit()">
            </label>
        </form>

        <div id="fileGrid">
            <?php
            $files = array_diff(scandir('uploads'), ['.', '..']);
            foreach ($files as $file): ?>
                <div class="file-box">
                    <img src="uploads/<?= $file ?>" alt="" style="width: 100%; height: 80px; object-fit: cover;" />
                    <div style="font-size: 12px; word-wrap: break-word;"> <?= $file ?> </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>

<!-- upload.php -->
<?php
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: home.php");
    exit;
}

if ($_FILES['file']['name']) {
    $upload_dir = 'uploads/';
    $filename = basename($_FILES['file']['name']);
    move_uploaded_file($_FILES['file']['tmp_name'], $upload_dir . $filename);
}
header("Location: dashboard.php");
?>

<!-- logout.php -->
<?php
session_start();
session_destroy();
header("Location: home.php");
?>
