<?php
session_start();

// Redirect if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: signin.php?error=Please log in first.");
    exit();
}

// Check session timeout (30 mins = 1800 seconds)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > $_SESSION['expire_time'])) {
    session_unset();
    session_destroy();
    header("Location: signin.php?error=Session expired. Please log in again.");
    exit();
}

// Update last activity time
$_SESSION['last_activity'] = time();

require 'db.php';

// Fetch tickets for the logged-in user
$result = $conn->query("SELECT ticketid, title, description, priority, status FROM tickets WHERE user_id = {$_SESSION['user_id']}");
$tickets = $result->fetch_all(MYSQLI_ASSOC);
$conn->close();
?>



<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">
      <img src="https://cdn0.iconfinder.com/data/icons/entypo/96/b2-512.png" alt="" width="30" height="24">
    </a>
    <a class="navbar-brand" href="#">Bablek Ticketing System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse justify-content-center">
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" href="create.php">Create</a>
        </li>
      </ul>
    </div>
    <div>
      <form action="signout.php" method="POST">
        <button type="submit" class="btn btn-outline-danger">Sign Out</button>
    </div>
  </div>
</nav>

<main>
  <div class="container mt-5">
    <h2 class="text-center mb-4">Dashboard</h2>
  <div class="w-75 p-3 mx-auto mt-3">
    <div class="d-flex justify-content-center">
      <table class="table table-bordered text-center">
        <thead class="table-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Status</th>
            <th scope="col">Priority</th>
            <th scope="col">Actions</th>
          </tr>
        </thead>
        <tbody>
  <?php if (!empty($tickets)): ?>
    <?php $i = 1; ?>
    <?php foreach ($tickets as $ticket): ?>
      <tr>
        <th scope="row"><?php echo $i++ ?></th>
        <td><?php echo htmlspecialchars($ticket['title']); ?></td>
        <td><?php echo htmlspecialchars($ticket['description']); ?></td>
        <td><?php echo htmlspecialchars($ticket['status']); ?></td>
        <td><?php echo htmlspecialchars($ticket['priority']); ?></td> 
        <td>
          <a href="edit.php?id=<?php echo $ticket['ticketid']; ?>" class="btn btn-primary me-md-3">Edit</a>
          <a href="delete.php?id=<?php echo $ticket['ticketid']; ?>" class="btn btn-danger">Delete</a>
        </td>
      </tr>
    <?php endforeach; ?>
  <?php else: ?>
    <tr><td colspan="6">No tickets found.</td></tr>
  <?php endif; ?>
</tbody>

      </table>
    </div>
  </div>
</main>
</body>
</html>
