<?php
require_once 'functions.php';
require_login();

$input = json_decode(file_get_contents('php://input'), true);
$id = (int)($input['id'] ?? 0);
$action = $input['action'] ?? '';

if(!$id || !$action){
    echo json_encode(['success'=>false,'message'=>'Invalid request']);
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM items WHERE id = ? LIMIT 1');
$stmt->execute([$id]);
$item = $stmt->fetch();
if(!$item){
    echo json_encode(['success'=>false,'message'=>'Item not found']);
    exit;
}

if($action === 'found'){
    // set to 'found' (anyone can mark found)
    if($item['status'] === 'found'){
        echo json_encode(['success'=>false,'message'=>'Item already marked found']);
        exit;
    }
    $stmt = $pdo->prepare('UPDATE items SET status = ?, found_by = ?, found_at = NOW() WHERE id = ?');
    $stmt->execute(['found', $_SESSION['student_id'], $id]);
    echo json_encode(['success'=>true,'message'=>'Item marked as found! The owner will be notified.']);
    exit;
}

if($action === 'returned'){
    // only the owner can mark returned
    if($item['student_id'] != $_SESSION['student_id']){
        echo json_encode(['success'=>false,'message'=>'Only the owner can mark it returned.']);
        exit;
    }
    if($item['status'] === 'returned'){
        echo json_encode(['success'=>false,'message'=>'Item already marked returned']);
        exit;
    }
    $stmt = $pdo->prepare('UPDATE items SET status = ? WHERE id = ?');
    $stmt->execute(['returned', $id]);
    echo json_encode(['success'=>true,'message'=>'Item marked as returned. Thank you!']);
    exit;
}

echo json_encode(['success'=>false,'message'=>'Unknown action']);
