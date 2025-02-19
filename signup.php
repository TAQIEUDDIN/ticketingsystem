<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <h2 class="text-center mb-4">Sign Up</h2>
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="mb-3">
                                <label for="fname" class="form-label">First Name</label>
                                <input type="text" id="fname" name="fname" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="lname" class="form-label">Last Name</label>
                                <input type="text" id="lname" name="lname" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" id="username" name="username" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" id="email" name="email" class="form-control" required>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" name="password" class="form-control" required autocomplete="off">
                            </div>

                            <div class="mb-3">
                                <label for="cpassword" class="form-label">Confirm Password</label>
                                <input type="password" id="cpassword" name="cpassword" class="form-control" required autocomplete="off">
                            </div>

                            <div class="mb-3">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" id="phone" name="phone" class="form-control" required>
                            </div>

                            <div class="form-check mb-3">
                                <input type="checkbox" id="agree" name="agree" class="form-check-input" required>
                                <label for="agree" class="form-check-label">I agree to the terms and conditions</label>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Sign Up</button>
                        </form>
                        <div class="text-center mt-3">
                            <p>Already have an account? <a href="signin.php">Sign In</a></p>
                        </div>
                    </div>
                </div>
                <div id="error-container" class="mt-3"></div>
            </div>
        </div>
    </div>
    <script src="signup.js"></script>
</body>
</html>

<?php
    require 'db.php'; // Include database connection
   
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Get form data and sanitize
        $fname = htmlspecialchars(trim($_POST["fname"]));
        $lname = htmlspecialchars(trim($_POST["lname"]));
        $username = htmlspecialchars(trim($_POST["username"]));
        $email = filter_var(trim($_POST["email"]), FILTER_SANITIZE_EMAIL);
        $password = trim($_POST["password"]);
        $cpassword = trim($_POST["cpassword"]);
        $phone = htmlspecialchars(trim($_POST["phone"]));
   
        // Hash the password
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);
   
        // Prepare SQL statement to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO users (fname, lname, username, email, password, phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $fname, $lname, $username, $email, $hashed_password, $phone);
   
        // Execute and check success
        if ($stmt->execute()) {
            header("Location: signin.php?success=1");
            exit();
        } else {
            echo "Error: " . $stmt->error;
        }
   
        // Close statement & connection
        $stmt->close();
        $conn->close();
    }
?>
