<?php

// Include functions file
include 'includes/functions.php';

// Fetch user from the database
$users = getLoggedUser($conn);

if (!$users) {
    header("Location: login.php"); // Redirect if user is not logged in
    exit();
}

// Update profile if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $firstName = trim($_POST['first_name']);
    $lastName = trim($_POST['last_name']);
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $role = trim($_POST['role']);

    // Validate inputs (you can add more validation as needed)
    if (empty($firstName) || empty($lastName) || empty($username) || empty($email) || empty($password) || empty($role)) {
        $error = "All fields are required.";
    } else {
        // Update the user in the database
        $query = "UPDATE users SET first_name = ?, last_name = ?, username = ?, email = ?, password_hash = ?, role = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT); // Hash the password before saving
        $stmt->bind_param("ssssssi", $firstName, $lastName, $username, $email, $hashedPassword, $role, $users['id']);

        if ($stmt->execute()) {
            // Optionally, you can add a success message
            $success = "Profile updated successfully.";
            // Update the local user array
            $users['first_name'] = $firstName;
            $users['last_name'] = $lastName;
            $users['username'] = $username;
            $users['email'] = $email;
            $users['role'] = $role;
        } else {
            $error = "Error updating profile: " . $conn->error;
        }
    }
}

// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="text-muted text-uppercase mt-4 mb-3">Edit Profile</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="profile.php">Profile</a>
        </li>
        <li class="breadcrumb-item active">Edit Profile</li>
    </ol>

    <!-- Display error or success messages -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger" role="alert"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <div class="alert alert-success" role="alert"><?php echo htmlspecialchars($success); ?></div>
    <?php endif; ?>

    <!-- Edit Profile Form -->
    <div class="card mb-4">
        <div class="card-header">
            <h5 class="text-uppercase text-muted mb-0">Edit Information</h5>
        </div>
        <div class="card-body">
            <form method="POST" action="">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="first_name" class="form-label">First Name</label>
                        <input type="text" class="form-control" id="first_name" name="first_name" value="<?php echo htmlspecialchars($users['first_name']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="last_name" class="form-label">Last Name</label>
                        <input type="text" class="form-control" id="last_name" name="last_name" value="<?php echo htmlspecialchars($users['last_name']); ?>" required>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="username" class="form-label">Username</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($users['username']); ?>" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($users['email']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control" id="password" name="password_hash" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="role" class="form-label">Role</label>
                        <input type="text" class="form-control" id="role" name="role" value="<?php echo htmlspecialchars($users['role']); ?>" required>
                    </div>
                </div>
               
                
                <button type="submit" class="btn btn-sm btn-primary">Update Profile</button>
                <a href="profile.php" class="btn btn-sm btn-outline-dark">Cancel</a>
            </form>
        </div>
    </div>
</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>
