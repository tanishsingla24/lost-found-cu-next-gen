<?php
require_once 'functions.php';
ensure_session();
$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = trim($_POST['uid'] ?? '');
    $name = trim($_POST['name'] ?? '');
    $password = ($_POST['password'] ?? '');
    $confirm = ($_POST['confirm_password'] ?? '');

    if ($uid === '' || $name === '' || $password === '' || $confirm === '') {
        $error = "All fields are required.";
    } elseif (!sanitize_uid($uid)) {
        $error = "UID format is invalid (should be like 00CS12345).";
    } elseif ($password !== $confirm) {
        $error = "Passwords do not match.";
    } else {
        // check existing uid
        $stmt = $pdo->prepare("SELECT id FROM students WHERE uid = ? LIMIT 1");
        $stmt->execute([$uid]);
        if ($stmt->fetch()) {
            $error = "UID already registered. Please login instead.";
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO students (uid, name, password) VALUES (?, ?, ?)");
            $stmt->execute([$uid, $name, $hash]);
            $success = "Registration successful. You can now login.";
        }
    }
}
?>
<!doctype html>
<html>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Register - Lost & Found CU</title>
<link rel="stylesheet" href="assets/css.css">
<style>
  body {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
  }
  .auth-container {
    width: 100%;
    max-width: 400px;
  }
  .auth-container .card {
    margin: 0;
  }
  .divider {
    margin: 20px 0;
    padding-top: 20px;
    border-top: 1px solid var(--gray-200);
    text-align: center;
    color: var(--gray-600);
    font-size: 14px;
  }
</style>
</head>
<body>
<div class="auth-container">
  <div class="card">
    <h2 style="text-align: center; margin-bottom: 20px;">📝 Create Account</h2>
    <?php if ($error): ?><div class="err"><?=htmlspecialchars($error)?></div><?php endif; ?>
    <?php if ($success): ?><div class="ok"><?=htmlspecialchars($success)?></div><?php endif; ?>

    <?php if (!$success): ?>
    <form method="post">
      <div>
        <label>UID <span class="small">(Format: 00xx00000)</span></label>
        <input type="text" name="uid" required maxlength="9" placeholder="e.g., 00CS12345" autofocus>
      </div>

      <div>
        <label>Full Name</label>
        <input type="text" name="name" required placeholder="Your complete name">
      </div>

      <div>
        <label>Password</label>
        <input type="password" name="password" required placeholder="Create a strong password">
      </div>

      <div>
        <label>Confirm Password</label>
        <input type="password" name="confirm_password" required placeholder="Re-enter your password">
      </div>

      <button type="submit" class="btn" style="width: 100%; margin-top: 16px; font-size: 15px;">Create Account</button>
    </form>
    <?php endif; ?>

    <div class="divider">Already have an account?</div>
    <a href="login.php" style="display: block; text-align: center; padding: 10px; color: var(--primary); font-weight: 600;">Login here →</a>
  </div>
</div>
</body>
</html>
</body>
</html>
