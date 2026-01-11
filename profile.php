<?php
require_once 'functions.php';
require_login();

$stmt = $pdo->prepare('SELECT * FROM items WHERE student_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['student_id']]);
$my = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Posts - Lost & Found CU</title>
<link rel="stylesheet" href="assets/css.css">
</head>
<body>
<div class="container">
  <header class="topbar">
    <div><strong>📂 My Posts</strong></div>
    <div><a href="dashboard.php" style="color: white; font-weight: 600;">⬅ Dashboard</a></div>
  </header>
  <main>
    <div class="card">
    <?php if(empty($my)): ?>
      <p style="text-align: center; color: var(--gray-600); padding: 40px 0;">
        📭 You haven't posted any items yet.<br>
        <a href="report_item.php" style="color: var(--primary); font-weight: 600;">Start reporting an item →</a>
      </p>
    <?php else: ?>
      <h2 style="margin-bottom: 20px;">Your Posted Items (<?=count($my)?>)</h2>
    <?php endif; ?>
    <?php foreach($my as $it): ?>
      <div class="item">
        <h3>
          <?=htmlspecialchars($it['title'])?>
          <span class="status-badge"><?=htmlspecialchars($it['status'])?></span>
        </h3>
        <?php if($it['description']): ?>
          <p><?=nl2br(htmlspecialchars($it['description']))?></p>
        <?php endif; ?>
        <div><strong>📍 Location:</strong> <?=htmlspecialchars($it['location'])?></div>
        <?php if($it['photo']): ?><img src="<?=htmlspecialchars($it['photo'])?>" class="item-photo" alt="Item photo"><?php endif; ?>
      </div>
    <?php endforeach; ?>
    </div>
  </main>
</div>
</body>
</html>
