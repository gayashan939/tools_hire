<?php
// process-rental.php
require_once 'config/database.php';
require_once 'includes/functions.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tool_id = $_POST['tool_id'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;
    $total_cost = $_POST['total_cost'] ?? 0;
    
    // In a real app, users must be logged in to rent.
    // For this prototype, we'll use a dummy user ID if not logged in.
    $user_id = $_SESSION['user_id'] ?? 2; // Assuming ID 2 is a demo customer

    if ($tool_id && $start_date && $end_date) {
        try {
            $stmt = $pdo->prepare("INSERT INTO rentals (tool_id, user_id, start_date, end_date, total_cost, status) 
                                   VALUES (?, ?, ?, ?, ?, 'pending')");
            $stmt->execute([$tool_id, $user_id, $start_date, $end_date, $total_cost]);
            
            // Redirect to a success page
            header("Location: rental-success.php?id=" . $pdo->lastInsertId());
            exit;
        } catch (PDOException $e) {
            die("Error processing rental: " . $e->getMessage());
        }
    }
}

header('Location: catalogue.php');
exit;
?>
