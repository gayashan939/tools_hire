<?php
// submit-review.php
require_once 'config/database.php';
require_once 'includes/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tool_id = $_POST['tool_id'] ?? null;
    $rating = $_POST['overall_rating'] ?? 5;
    $comment = $_POST['comment'] ?? '';
    
    // In a real app, you'd get the user_id from session
    // For this prototype, we'll allow guest reviews or use a dummy ID
    $user_id = $_SESSION['user_id'] ?? null;

    if ($tool_id) {
        $stmt = $pdo->prepare("INSERT INTO reviews (tool_id, user_id, overall_rating, comment, status) 
                               VALUES (?, ?, ?, ?, 'pending')");
        $stmt->execute([$tool_id, $user_id, $rating, $comment]);
        
        // Success redirect with message
        header("Location: tool-detail.php?id=$tool_id&msg=Review submitted for moderation.");
        exit;
    }
}

header('Location: catalogue.php');
exit;
?>
