<?php
session_start();
require 'db.php';

// Redirect if user is already logged in
if (isset($_SESSION['user_id'])) {
    header("Location: dashboardticket.php");
    exit();
}

$error = null; // Initialize error variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = trim($_POST['password']);

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verify password
        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['last_activity'] = time(); // Track session activity
            $_SESSION['expire_time'] = 1800; // 30-minute session timeout

            header("Location: dashboardticket.php");
            exit();
        } else {
            $_SESSION['error'] = "Invalid username or password.";
        }
    } else {
        $_SESSION['error'] = "Invalid username or password.";
    }

    $stmt->close();
    $conn->close();
    header("Location: signin.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Sign In</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <div class="input-group mb-3">
                                    <input class="form-control password" id="password" type="password" name="password" required />
                                    <span class="input-group-text togglePassword" id="">
                                        <i data-feather="eye" style="cursor: pointer"></i>
                                    </span>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Sign In</button>
                        </form>

                        <!-- Display error messages -->
                        <?php if (isset($_SESSION['error'])): ?>
                            <div class="alert alert-danger mt-3"><?php echo $_SESSION['error']; unset($_SESSION['error']); ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="text-center p-3">
                        <p>Don't have an account? <a href="signup.php">Sign Up</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        // Initialize Feather icons
        feather.replace();

        // Password toggle functionality
        document.querySelector('.togglePassword').addEventListener('click', function() {
            const password = document.querySelector('.password');
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            
            // Toggle the eye icon
            const eyeIcon = this.querySelector('i');
            if (type === 'password') {
                eyeIcon.setAttribute('data-feather', 'eye');
            } else {
                eyeIcon.setAttribute('data-feather', 'eye-off');
            }
            feather.replace();
        });
    </script>
</body>
</html>
