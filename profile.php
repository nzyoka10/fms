<?php
// Include functions file
include 'includes/functions.php';

// Fetch user from the database
$users = getLoggedUser($conn);

// Check if user is logged in
if (!$users) {
    echo "<p class='text-danger'>You must be logged in to view this page.</p>";
    exit;
}

// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="text-muted text-uppercase mt-4 mb-3">Account Profile</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="profile.php">Profile</a>
        </li>
    </ol>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="text-uppercase text-muted mb-0">
                <?php echo htmlspecialchars($users['first_name']) . ' ' . htmlspecialchars($users['last_name']); ?>
            </h5>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <th scope="row">Created</th>
                        <td><?php echo htmlspecialchars($users['created_at']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Full Name</th>
                        <td class="text-capitalize"><?php echo htmlspecialchars($users['first_name']) . ' ' . htmlspecialchars($users['last_name']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Username</th>
                        <td class="text-capitalize"><?php echo htmlspecialchars($users['username']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Email</th>
                        <td><?php echo htmlspecialchars($users['email']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">Password</th>
                        <td><?php echo htmlspecialchars($users['password_hash']); ?></td>
                    </tr>
                    <tr>
                        <th scope="row">User Role</th>
                        <td class="text-capitalize"><?php echo htmlspecialchars($users['role']); ?></td>
                    </tr>
                </tbody>
            </table>
            <a href="editProfile.php" class="btn btn-sm btn-outline-primary">Edit Profile</a>
            <a href="users.php" class="btn btn-sm btn-outline-dark">Back to List</a>
        </div>
    </div>
</main>

<!-- SweetAlert script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Footer -->
<?php include './includes/footer.php'; ?>
