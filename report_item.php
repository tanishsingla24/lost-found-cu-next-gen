<?php
require_once 'functions.php';
require_login();

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $location = trim($_POST['location'] ?? '');
    $student_id = $_SESSION['student_id'];
    $photo = null;
    $photo_hash = null;

    if ($title === '' || $location === '') {
        $error = "Please fill in all required fields.";
    } else {
        // Handle image upload if provided
        if (!empty($_FILES['photo']['name'])) {
            $tmp = $_FILES['photo']['tmp_name'];
            $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
            $filename = time() . '_' . substr(md5(uniqid('', true)), 0, 10) . '.' . $ext;

            // Folder path (you will create 'uploads' folder manually)
            $uploadDir = __DIR__ . '/uploads';
            $targetPath = $uploadDir . '/' . $filename;

            // Generate hash for duplicate check
            $photo_hash = hash_file('sha256', $tmp);

            if (file_exists($uploadDir)) {
                if (!move_uploaded_file($tmp, $targetPath)) {
                    $error = "Failed to save uploaded file. Please check permissions.";
                } else {
                    $photo = 'uploads/' . $filename;
                }
            } else {
                $error = "Uploads folder not found. Please create a folder named 'uploads' in your project directory.";
            }
        }

        if ($error === '') {
            // Check for duplicate items (same photo/title/location)
            $dup = check_duplicate_item($pdo, $title, $location, $photo_hash);
            if ($dup) {
                $error = "A similar item was already reported recently (possible duplicate).";
            } else {
                // Insert into DB
                $stmt = $pdo->prepare("INSERT INTO items (student_id, title, description, location, photo, photo_hash, status) VALUES (?, ?, ?, ?, ?, ?, 'lost')");
                $stmt->execute([$student_id, $title, $description, $location, $photo, $photo_hash]);
                $success = "Item reported successfully!";
            }
        }
    }
}
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Report Item - Lost & Found CU</title>
<link rel="stylesheet" href="assets/css.css">
</head>
<body>
<div class="container">
  <header class="topbar">
    <div>📢 Report an Item</div>
    <div><a href="dashboard.php" style="color: white; font-weight: 600;">⬅ Dashboard</a></div>
  </header>

  <div class="card">
    <h2>📝 Report Item</h2>
    <?php if ($error): ?><div class="err">❌ <?= htmlspecialchars($error) ?></div><?php endif; ?>
    <?php if ($success): ?><div class="ok-success">✅ <strong><?= htmlspecialchars($success) ?></strong></div><?php endif; ?>

    <form method="post" enctype="multipart/form-data">
      <div>
        <label>Item Title <span class="small">(required)</span></label>
        <input type="text" name="title" required placeholder="e.g., Blue Wallet, AirPods, Red Backpack">
      </div>

      <div>
        <label>Description</label>
        <textarea name="description" placeholder="Describe the item: color, brand, special marks, condition, etc."></textarea>
      </div>

      <div>
        <label>Location <span class="small">(required)</span></label>
        <input type="text" name="location" required placeholder="e.g., Block A3, Room 204, Near Main Gate">
      </div>

      <div>
        <label>Upload Photo <span class="small">(optional)</span></label>
        <input type="file" name="photo" accept="image/*">
      </div>

      <button type="submit" class="btn" style="width: 100%; margin-top: 16px; font-size: 15px;">✈️ Submit Report</button>
    </form>
  </div>
</div>
</body>
</html>
