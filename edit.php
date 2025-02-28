<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php");
    exit();
}

// Check if ID exists
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("Error: Ticket ID is missing.");
}

$ticketid = $_GET['id'];

// Fetch existing ticket details
$sql = "SELECT title, description, priority FROM tickets WHERE ticketid = ? AND user_id = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Error in preparing statement: " . $conn->error);
}

$stmt->bind_param("ii", $ticketid, $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 0) {
    die("Error: Ticket not found or you don't have permission to edit this ticket.");
}

$ticket = $result->fetch_assoc();
$existing_title = $ticket['title'];
$existing_description = $ticket['description'];
$existing_priority = $ticket['priority'];

$stmt->close();

// Check if form was submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];

    // Prepare UPDATE statement
    $sql = "UPDATE tickets SET title = ?, description = ?, priority = ? WHERE ticketid = ? AND user_id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Error in preparing statement: " . $conn->error);
    }

    // Bind parameters (3 strings, 2 integers)
    $stmt->bind_param("sssii", $title, $description, $priority, $ticketid, $_SESSION['user_id']);

    if ($stmt->execute()) {
        header("Location: dashboardticket.php");
        exit();
    } else {
        echo "Error updating ticket: " . $conn->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Edit Ticket</h3>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($existing_title); ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="description" class="form-control" required><?php echo htmlspecialchars($existing_description); ?></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Priority</label>
                            <select name="priority" class="form-control" required>
                                <option value="Low" <?php if ($existing_priority == 'Low') echo 'selected'; ?>>Low</option>
                                <option value="Medium" <?php if ($existing_priority == 'Medium') echo 'selected'; ?>>Medium</option>
                                <option value="High" <?php if ($existing_priority == 'High') echo 'selected'; ?>>High</option>
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
