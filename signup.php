<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/feather-icons@4.28.0/dist/feather.min.js" integrity="sha384-uO3SXW5IuS1ZpFPKugNNWqTZRRglnUJK6UAZ/gxOX80nxEkN9NcGZTftn6RzhGWE" crossorigin="anonymous"></script>
    <link href="/styles.css" rel="stylesheet">
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
                                <div class="input-group">
                                    <input type="password" id="password" name="password" class="form-control password1" required autocomplete="off">
                                    <span class="input-group-text togglePassword1" style="cursor: pointer">
                                        <i data-feather="eye"></i>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label for="cpassword" class="form-label">Confirm Password</label>
                                <div class="input-group">
                                    <input type="password" id="cpassword" name="cpassword" class="form-control password2" required autocomplete="off">
                                    <span class="input-group-text togglePassword2" style="cursor: pointer">
                                        <i data-feather="eye"></i>
                                    </span>
                                </div>
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

<script>
    // Initialize Feather icons
    feather.replace();

    // Password toggle functionality for both password fields
    function togglePassword(toggleButton, passwordInput) {
        toggleButton.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            
            // Toggle the eye icon
            const eyeIcon = this.querySelector('i');
            if (type === 'password') {
                eyeIcon.setAttribute('data-feather', 'eye');
            } else {
                eyeIcon.setAttribute('data-feather', 'eye-off');
            }
            feather.replace();
        });
    }

    // Initialize toggle functionality for both password fields
    togglePassword(document.querySelector('.togglePassword1'), document.querySelector('.password1'));
    togglePassword(document.querySelector('.togglePassword2'), document.querySelector('.password2'));
</script>
</body>
</html>
