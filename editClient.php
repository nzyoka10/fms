<?php
// Start the session
session_start();

// Include functions file
include 'includes/functions.php';

// Fetch the client ID from the URL parameter
$id = $_GET['id'];
$client = getClientById($id); // Retrieve client details

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Call the updateClient function
    updateClient($id, $name, $email, $phone, $address);

    // Redirect to the dashboard on successful update
    header("Location: clients.php");
    exit();
}
?>

<!doctype html>
<html lang="en">
<head>
    <title>Edit Client - FMS</title>
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
            <div class="col-md-8 col-lg-6">
                <div class="login-wrap p-4 p-md-5">

                    <!-- User Icon -->
                    <div class="icon d-flex align-items-center justify-content-center">
                        <span id="icon" class="fa fa-pencil-square-o"></span>
                    </div>

                    <!-- Form Title -->
                    <h3 class="text-center mb-4">Edit Client</h3>

                    <!-- Update Client Form -->
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $id; ?>" class="login-form">

                        <!-- Name Field -->
                        <div class="form-group mb-3">
                            <label for="name" class="sr-only">Full Name</label>
                            <input type="text" class="form-control rounded-left" id="name" name="name" aria-label="Full Name" placeholder="Full Name" value="<?php echo htmlspecialchars($client['full_name']); ?>" required>
                        </div>

                        <!-- Email Field -->
                        <div class="form-group mb-3">
                            <label for="email" class="sr-only">Email</label>
                            <input type="email" class="form-control rounded-left" id="email" name="email" aria-label="Email" placeholder="Email" value="<?php echo htmlspecialchars($client['email']); ?>" required>
                        </div>

                        <!-- Phone Field -->
                        <div class="form-group mb-3">
                            <label for="phone" class="sr-only">Phone</label>
                            <input type="text" class="form-control rounded-left" id="phone" name="phone" aria-label="Phone" placeholder="Phone" value="<?php echo htmlspecialchars($client['phone']); ?>" required>
                        </div>

                        <!-- Address Field -->
                        <div class="form-group mb-3">
                            <label for="address" class="sr-only">Address</label>
                            <input type="text" class="form-control rounded-left" id="address" name="address" aria-label="Address" placeholder="Address" value="<?php echo htmlspecialchars($client['address']); ?>" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <input type="submit" class="btn btn-sm btn-success p-3 px-5" value="Update Client">
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
