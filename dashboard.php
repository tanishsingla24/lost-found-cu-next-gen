<?php
require_once 'functions.php';
require_login();
$user = current_user();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboard - Lost and Found CU</title>
<link rel="stylesheet" href="assets/css.css">
</head>
<body>
<div class="container">
  <header class="topbar">
    <div>
      <strong>👋 Welcome, <?=htmlspecialchars($user['name'] ?? ($_SESSION['name'] ?? 'Student'))?></strong>
      <br><code style="font-size: 11px;">ID: <?=htmlspecialchars($_SESSION['uid'] ?? 'N/A')?></code>
    </div>
    <div style="display: flex; gap: 10px;">
      <a href="profile.php" style="color: white; font-weight: 600;">📂 My Posts</a>
      <a href="logout.php" style="color: white; font-weight: 600;">🚪 Logout</a>
    </div>
  </header>

  <main>
    <div class="card">
      <h2 style="margin-bottom: 16px;">Dashboard</h2>
      <p style="margin-bottom: 20px; color: var(--gray-600);">Select an action below to get started:</p>
      <div class="btn-group" style="flex-direction: column;">
        <a class="btn" href="report_item.php" style="width: 100%; justify-content: flex-start;">
          <span>📝</span> Report Lost or Found Item
        </a>
        <a class="btn" href="list_items.php" style="width: 100%; justify-content: flex-start;">
          <span>🔍</span> Browse All Items
        </a>
        <a class="btn" href="profile.php" style="width: 100%; justify-content: flex-start;">
          <span>📂</span> My Posts
        </a>
      </div>
    </div>
  </main>
</div>
</body>
</html>
