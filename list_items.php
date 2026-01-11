<?php
require_once 'functions.php';
require_login();

$q = trim($_GET['q'] ?? '');
$type = $_GET['type'] ?? '';
$params = [];
$sql = "SELECT i.*, s.uid as owner_uid, s.name as owner_name FROM items i JOIN students s ON s.id = i.student_id WHERE 1=1";

if($q){
    $sql .= " AND (i.title LIKE ? OR i.description LIKE ?)";
    $params[] = "%$q%"; $params[] = "%$q%";
}
if($type && in_array($type, ['lost','found','returned'])){
    $sql .= " AND i.status = ?";
    $params[] = $type;
}
$sql .= " ORDER BY i.created_at DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$items = $stmt->fetchAll();
?>
<!doctype html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Browse Items - Lost & Found CU</title>
<link rel="stylesheet" href="assets/css.css">
</head>
<body>
<div class="container">
  <header class="topbar">
    <div>
      <strong>👤 <?=htmlspecialchars($_SESSION['name'])?></strong>
      <br><code style="font-size: 11px;">ID: <?=htmlspecialchars($_SESSION['uid'])?></code>
    </div>
    <div style="display: flex; gap: 10px;">
      <a href="dashboard.php" style="color: white; font-weight: 600;">📊 Dashboard</a>
      <a href="logout.php" style="color: white; font-weight: 600;">🚪 Logout</a>
    </div>
  </header>
  <main>
    <div class="card">
      <h2 style="margin-bottom: 16px;">🔍 Search Items</h2>
      <form method="get">
        <input name="q" placeholder="Search by title or description..." value="<?=htmlspecialchars($q)?>">
        <select name="type">
          <option value="">All Items</option>
          <option value="lost" <?= $type==='lost'?'selected':'' ?>>Lost Items</option>
          <option value="found" <?= $type==='found'?'selected':'' ?>>Found Items</option>
          <option value="returned" <?= $type==='returned'?'selected':'' ?>>Returned</option>
        </select>
        <button class="btn" type="submit" style="width: auto;">Search</button>
      </form>
    </div>

    <div class="card">
      <?php if(empty($items)): ?>
        <p style="text-align: center; padding: 40px 0; color: var(--gray-600);">
          📭 No items found. Try adjusting your search filters.
        </p>
      <?php else: ?>
        <h2 style="margin-bottom: 16px;">Results (<?=count($items)?>)</h2>
      <?php endif; ?>
      
      <?php foreach($items as $it): ?>
        <div class="item" id="item_<?= $it['id'] ?>">
          <h3>
            <?=htmlspecialchars($it['title'])?>
            <span class="status-badge"><?=htmlspecialchars($it['status'])?></span>
          </h3>
          <?php if($it['description']): ?>
            <p><?=nl2br(htmlspecialchars($it['description']))?></p>
          <?php endif; ?>
          <div><strong>📍 Location:</strong> <?=htmlspecialchars($it['location'])?></div>
          <div><strong>👤 Posted by:</strong> <?=htmlspecialchars($it['owner_name'])?> (<?=htmlspecialchars($it['owner_uid'])?>)</div>
          <?php if($it['photo']): ?><img src="<?=htmlspecialchars($it['photo'])?>" class="item-photo" alt="Item photo"><?php endif; ?>

          <div style="margin-top: 12px; display: flex; gap: 8px; flex-wrap: wrap;">
          <?php if($it['student_id'] == $_SESSION['student_id']): ?>
              <?php if($it['status'] !== 'returned'): ?>
                <button class="btn" onclick="markReturned(<?= $it['id'] ?>)" style="background: #1976d2; color: white; font-weight: 700; font-size: 13px;">✅ Mark as Returned</button>
              <?php else: ?>
                <span style="background: #1976d2; color: white; padding: 8px 12px; border-radius: 6px; font-weight: 700; font-size: 13px;">✓ Returned</span>
              <?php endif; ?>
          <?php else: ?>
              <?php if($it['status'] === 'lost'): ?>
                <button class="btn" onclick="markFound(<?= $it['id'] ?>)" style="font-size: 13px;">🎯 I Found This</button>
              <?php else: ?>
                <span style="color: var(--gray-600); font-weight: 500; font-size: 13px;">✓ Handled</span>
              <?php endif; ?>
          <?php endif; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
  </main>
</div>

<script>
function apiPost(payload){
  return fetch('mark_found.php', {
    method:'POST',
    headers:{'Content-Type':'application/json'},
    body: JSON.stringify(payload)
  }).then(res => res.json());
}

function markFound(id){
  if(!confirm('Mark this item as found?')) return;
  apiPost({id:id, action:'found'}).then(j=>{
    alert(j.message);
    if(j.success) location.reload();
  });
}

function markReturned(id){
  if(!confirm('Mark this item as returned to the owner?')) return;
  apiPost({id:id, action:'returned'}).then(j=>{
    alert(j.message);
    if(j.success) location.reload();
  });
}
</script>
</body>
</html>
