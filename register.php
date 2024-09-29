<?php
// Include your database connection file and other required files here
// require 'config.php'; // Assuming you have a config.php for DB connection
require './includes/functions.php';

// Initialize error variables
$firstNameError = $lastNameError = $usernameError = $emailError = $passwordError = $confirmPasswordError = $roleError = '';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form inputs
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $confirmPassword = trim($_POST['confirm_password']);
    $role = trim($_POST['role']);

    // Validate inputs
    if (empty($firstName)) {
        $firstNameError = 'First Name is required.';
    }

    if (empty($lastName)) {
        $lastNameError = 'Last Name is required.';
    }

    if (empty($username)) {
        $usernameError = 'Username is required.';
    }

    if (empty($email)) {
        $emailError = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = 'Invalid email format.';
    }

    if (empty($password)) {
        $passwordError = 'Password is required.';
    } elseif (strlen($password) < 6) {
        $passwordError = 'Password must be at least 6 characters.';
    }

    if ($password !== $confirmPassword) {
        $confirmPasswordError = 'Passwords do not match.';
    }

    if (empty($role)) {
        $roleError = 'Role is required.';
    }

    // If no errors, proceed to register the user
    if (empty($firstNameError) && empty($lastNameError) && empty($usernameError) && empty($emailError) && empty($passwordError) && empty($confirmPasswordError) && empty($roleError)) {
        if (registerUser($firstName, $lastName, $username, $email, $password, $role)) {
            // Redirect to login page after successful registration
            header("Location: index.php"); // Redirect to login page
            exit(); // Ensure no further code is executed
        } else {
            // Handle registration failure (e.g., user already exists)
            $usernameError = 'Username or email already exists.';
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>FMS - Register</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    
    <!-- Fonts & CSS -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body>
    <section class="ftco-section">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="login-wrap p-4 p-md-5">
                        <div class="icon d-flex align-items-center justify-content-center">
                            <span class="fa fa-user-plus"></span>
                        </div>
                        <h4 class="text-center mb-4">Register Account</h4>

                        <form action="register.php" method="POST" class="login-form">
                            <div class="row g-3 mb-3">
                                <div class="col">
                                    <input type="text" class="form-control" name="first_name" placeholder="First Name" aria-label="First Name" required>
                                    <span class="text-danger"><?php echo isset($firstNameError) ? $firstNameError : ''; ?></span>
                                </div>
                                <div class="col">
                                    <input type="text" class="form-control" name="last_name" placeholder="Last Name" aria-label="Last Name" required>
                                    <span class="text-danger"><?php echo isset($lastNameError) ? $lastNameError : ''; ?></span>
                                </div>
                            </div>

                            <div class="mb-3">
                                <input type="text" class="form-control" name="username" placeholder="Username" required>
                                <span class="text-danger"><?php echo isset($usernameError) ? $usernameError : ''; ?></span>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col">
                                    <input type="email" class="form-control" name="email" placeholder="Email" required>
                                    <span class="text-danger"><?php echo isset($emailError) ? $emailError : ''; ?></span>
                                </div>
                                <div class="col">
                                    <select name="role" class="form-control" required>
                                        <option value="">Select Role</option>
                                        <option value="admin">Admin</option>
                                        <option value="staff">Staff</option>
                                    </select>
                                    <span class="text-danger"><?php echo isset($roleError) ? $roleError : ''; ?></span>
                                </div>
                            </div>

                            <div class="row g-3 mb-3">
                                <div class="col">
                                    <input type="password" class="form-control" name="password" placeholder="Password" required>
                                    <span class="text-danger"><?php echo isset($passwordError) ? $passwordError : ''; ?></span>
                                </div>
                                <div class="col">
                                    <input type="password" class="form-control" name="confirm_password" placeholder="Confirm Password" required>
                                    <span class="text-danger"><?php echo isset($confirmPasswordError) ? $confirmPasswordError : ''; ?></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <input type="submit" class="btn btn-success p-3 px-5" value="Sign Up">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="./assets/js/jquery.min.js"></script>
    <script src="./assets/js/popper.js"></script>
    <script src="./assets/js/bootstrap.min.js"></script>
    <script src="./assets/js/main.js"></script>
</body>
</html>
