<?php
require 'config.php';
session_start();

// Return JSON headers
header('Content-Type: application/json');

// Check authentication
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Login required']);
    exit;
}

// Get parameters
$content_type = $_POST['content_type'] ?? '';
$content_id = (int)($_POST['content_id'] ?? 0);
$value = (int)($_POST['value'] ?? 0);

// Validate inputs
if (
    !in_array($content_type, ['post', 'comment']) || 
    !$content_id || 
    !in_array($value, [1, -1])
) {
    echo json_encode(['success' => false, 'message' => 'Invalid parameters']);
    exit;
}

try {
    // Check if content exists
    $stmt = $pdo->prepare("SELECT id FROM {$content_type}s WHERE id = ?");
    $stmt->execute([$content_id]);
    if (!$stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Content not found']);
        exit;
    }

    // Handle vote
    $stmt = $pdo->prepare("
        INSERT INTO votes (user_id, content_type, content_id, value) 
        VALUES (?, ?, ?, ?) 
        ON DUPLICATE KEY UPDATE value = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $content_type, $content_id, $value, $value]);

    // Get updated counts
    $stmt = $pdo->prepare("
        SELECT 
            SUM(CASE WHEN value = 1 THEN 1 ELSE 0 END) AS likes,
            SUM(CASE WHEN value = -1 THEN 1 ELSE 0 END) AS dislikes,
            (SELECT value FROM votes 
             WHERE user_id = ? 
             AND content_type = ? 
             AND content_id = ?) AS user_vote
        FROM votes 
        WHERE content_type = ? 
        AND content_id = ?
    ");
    $stmt->execute([$_SESSION['user_id'], $content_type, $content_id, $content_type, $content_id]);
    $result = $stmt->fetch();

    echo json_encode([
        'success' => true,
        'likes' => $result['likes'],
        'dislikes' => $result['dislikes'],
        'user_vote' => $result['user_vote']
    ]);

} catch (PDOException $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Database error: ' . $e->getMessage()
    ]);
}
?>