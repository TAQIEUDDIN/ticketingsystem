<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <title>Create Ticket</title>
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Create Ticket</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label for="title" class="form-label">Title</label>
                            <input type="text" class="form-control" name="title">
                        </div>
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" name="description" rows="3"></textarea>
                        </div>
                        <div class="mb-4">
                            <label for="priority" class="form-label">Priority</label>
                            <select class="form-select" name="priority">
                                <option value="Low">Low</option>
                                <option value="Medium">Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div class="d-flex justify-content-between">
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="dashboardticket.php" class="btn btn-danger text-white">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>

<?php
session_start(); // Start the session
require 'db.php';

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect form data
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $user_id = $_SESSION['user_id']; // Get logged-in user's ID

    // Prepare SQL statement
    $stmt = $conn->prepare("INSERT INTO tickets (title, description, priority, user_id) VALUES (?, ?, ?, ?)");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters correctly
    $stmt->bind_param("sssi", $title, $description, $priority, $user_id);

    // Execute and check if successful
    if ($stmt->execute()) {
        header('Location: dashboardticket.php');
        exit();
    } else {
        echo "Failed to create ticket: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}
?>



