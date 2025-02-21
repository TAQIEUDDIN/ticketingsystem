<?php
require 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    die("Error: User not logged in.");
}

// Fetch tickets for the logged-in user
$result = $conn->query("SELECT ticketid, title, description, status FROM tickets WHERE user_id = {$_SESSION['user_id']}");
$tickets = $result->fetch_all(MYSQLI_ASSOC);
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
          <a class="nav-link" href="#">Home</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="create.php">Create</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Inbox</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Ticket History</a>
        </li>
      </ul>
    </div>
  </div>
</nav>

<main>
  <div class="w-75 p-3 mx-auto mt-3">
    <div class="d-flex justify-content-center">
      <table class="table table-bordered text-center">
        <thead class="table-dark">
          <tr>
            <th scope="col">#</th>
            <th scope="col">Title</th>
            <th scope="col">Description</th>
            <th scope="col">Status</th>
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
                <td><?php echo $ticket['status']; ?></td>
                <td>
                </td>
              </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr><td colspan="5">No tickets found.</td></tr>
          <?php endif; ?>
        </tbody>
      </table>
    </div>
  </div>
</main>
</body>
</html>
