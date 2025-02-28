<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Ticket ID is missing.");
}

$ticketid = $_GET['id'];

// Prepare DELETE statement
$sql = "DELETE FROM tickets WHERE ticketid = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error in preparing statement: " . $conn->error);
}

$stmt->bind_param("ii", $ticketid, $_SESSION['user_id']);

if ($stmt->execute()) {
    header("Location: dashboardticket.php");
    exit();
} else {
    echo "Error: " . $conn->error;
}

$stmt->close();
$conn->close();
?>
