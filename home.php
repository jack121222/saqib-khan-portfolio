<?php
require 'auth.php';
require 'config.php';

$userId = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM files WHERE user_id = ? ORDER BY uploaded_at DESC");
// $stmt->execute([$userId]);
$files = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>BEDRIVE Home</title>
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      background-color: #121212;
      color: #f0f0f0;
      font-family: Arial, sans-serif;
    }
    .topbar { background: #1f1f1f; padding: 10px 20px; display: flex; justify-content: space-between; }
    .sidebar { background: #1a1a1a; width: 220px; height: 100vh; position: fixed; padding: 20px; }
    .main-content { margin-left: 240px; padding: 20px; }
    .file-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(140px, 1fr)); gap: 15px; }
    .file-tile { background: #2a2a2a; padding: 10px; border-radius: 8px; text-align: center; color: #eee; }
    .file-tile:hover { background: #333; }
    .material-icons { font-size: 40px; color: #90caf9; }
  </style>
</head>
<body>

<div class="topbar">
  <div><strong>BEDRIVE</strong></div>
  <div>
    <form action="upload.php" method="post" enctype="multipart/form-data">
      <input type="file" name="file" required />
      <button type="submit" class="btn btn-sm btn-primary">Upload</button>
    </form>
  </div>
</div>

<div class="sidebar">
  <p><strong>Navigation</strong></p>
  <ul class="nav flex-column">
    <li class="nav-item"><a href="home.php" class="nav-link text-light">All Files</a></li>
    <li class="nav-item"><a href="logout.php" class="nav-link text-danger">Logout</a></li>
  </ul>
</div>

<div class="main-content">
  <h4>Your Files</h4>
  <div class="file-grid">
    <?php if ($files): ?>
      <?php foreach ($files as $file): ?>
        <div class="file-tile">
          <span class="material-icons">
            <?php
              $ext = pathinfo($file['file_name'], PATHINFO_EXTENSION);
              echo match(strtolower($ext)) {
                'jpg', 'jpeg', 'png', 'gif' => 'image',
                'pdf' => 'picture_as_pdf',
                'mp4', 'avi' => 'movie',
                'mp3' => 'audiotrack',
                'zip', 'rar' => 'folder',
                default => 'insert_drive_file',
              };
            ?>
          </span>
          <div><?= htmlspecialchars($file['file_name']) ?></div>
          <a class="btn btn-sm btn-outline-light mt-1" href="<?= $file['file_path'] ?>" download>Download</a>
        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>No files uploaded yet.</p>
    <?php endif; ?>
  </div>
</div>

</body>
</html>
