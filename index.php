<?php
// Start the session
session_start();

// Include functions file
include 'includes/functions.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Call the loginUser function
    if (loginUser($username, $password)) {
        // Redirect to the dashboard on successful login
        header("Location: dashboard.php");
        exit();
    } else {
        // Redirect back to index.php with an error message if login fails
        header("Location: index.php?error=1");
        exit();
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>FMS - Account</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Lato:300,400,700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- CSS files -->
    <link rel="stylesheet" href="./assets/css/style.css">
    <link rel="stylesheet" href="./assets/css/main.css">
</head>

<body>
<section class="ftco-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="login-wrap p-4 p-md-5">

                    <!-- User Icon -->
                    <div class="icon d-flex align-items-center justify-content-center">
                        <span id="icon" class="fa fa-user-o"></span>
                    </div>

                    <!-- Form Title -->
                    <h3 class="text-center mb-4">FMS</h3>

                    <!-- Check for error messages -->
                    <?php if (isset($_GET['error']) && $_GET['error'] == 1): ?>
                        <div class="alert alert-danger" role="alert">
                            Incorrect username or password. Please try again.
                        </div>
                    <?php endif; ?>

                    <!-- Login Form -->
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="login-form">

                        <!-- Username Field -->
                        <div class="form-group mb-3">
                            <label for="username" class="sr-only">Username</label>
                            <input type="text" class="form-control rounded-left" id="username" name="username" aria-label="Username" placeholder="Username" required>
                        </div>

                        <!-- Password Field -->
                        <div class="form-group mb-3 d-flex">
                            <label for="password" class="sr-only">Password</label>
                            <input type="password" class="form-control rounded-left" id="password" name="password" aria-label="Password" placeholder="Password" required>
                        </div>

                        <!-- Forgot Password & Register Link -->
                        <div class="form-group d-md-flex mb-3">
                            <div class="w-100 text-left">
                                <span>Don't have an account?&nbsp;
                                    <a href="register.php" class="text-muted">Register</a>
                                </span>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <input type="submit" class="btn btn-success p-3 px-5" value="Sign In">
                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
</section>

<!-- JS Files -->
<script src="./assets/js/jquery.min.js"></script>
<script src="./assets/js/popper.js"></script>
<script src="./assets/js/bootstrap.min.js"></script>
<script src="./assets/js/main.js"></script>
</body>
</html>
