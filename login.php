<?php
require_once 'functions.php';
ensure_session();
$err = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $uid = trim($_POST['uid'] ?? '');
    $pwd = $_POST['password'] ?? '';

    if ($uid === '' || $pwd === '') {
        $err = "Please enter both UID and password.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM students WHERE uid = ? LIMIT 1");
        $stmt->execute([$uid]);
        $user = $stmt->fetch();

        if (!$user) {
            $err = "No account found for this UID.";
        } else {
            $stored = $user['password'] ?? '';

            // verify using password_verify (bcrypt)
            if ($stored !== '' && password_verify($pwd, $stored)) {
                // success
            } elseif ($stored !== '' && md5($pwd) === $stored) {
                // legacy md5 match — upgrade
                $newHash = password_hash($pwd, PASSWORD_DEFAULT);
                $up = $pdo->prepare("UPDATE students SET password = ? WHERE id = ?");
                $up->execute([$newHash, $user['id']]);
            } else {
                $err = "Incorrect password.";
            }

            if ($err === '') {
                // set session
                session_regenerate_id(true);
                $_SESSION['student_id'] = $user['id'];
                $_SESSION['uid'] = $user['uid'];
                $_SESSION['name'] = $user['name'] ?? 'Student';

                // redirect
                header('Location: dashboard.php');
                exit;
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
<title>Login - Lost & Found CU</title>
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
    <h2 style="text-align: center; margin-bottom: 20px;">🔐 Student Login</h2>
    <?php if ($err): ?><div class="err"><?=htmlspecialchars($err)?></div><?php endif; ?>
    <form method="post">
      <div>
        <label>UID <span class="small">(Format: 00xx00000)</span></label>
        <input name="uid" required placeholder="e.g., 00CS12345" autofocus>
      </div>

      <div>
        <label>Password</label>
        <input name="password" type="password" required placeholder="Enter your password">
      </div>

      <button type="submit" class="btn" style="width: 100%; margin-top: 16px; font-size: 15px;">Login</button>
    </form>

    <div class="divider">Don't have an account?</div>
    <a href="register.php" style="display: block; text-align: center; padding: 10px; color: var(--primary); font-weight: 600;">Register here →</a>
  </div>
</div>
</body>
</html>
