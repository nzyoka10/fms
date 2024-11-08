<?php
// Include functions file
include 'includes/functions.php';

// Fetch users from the database
$users = getUsers($conn);

// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="text-muted text-uppercase mt-4 mb-3">Account Users</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="users.php">Users</a>
        </li>
    </ol>

    <div class="w-100 text-left">
        <span>
            <!-- Don't have an account?&nbsp; -->
            <a class="btn btn-sm btn-dark" href="register.php" class="text-muted">New User</a>
        </span>
    </div>

    <!-- Table to display account users -->
    <div class="table table-responsive">
        <?php if (!empty($users)): ?>
            <table class="table table-striped table-hover table-lg mt-2">
                <thead class="table-dark">
                    <tr>
                        <th>Sn#</th>
                        <th>Date Created</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <!-- <th>Actions</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                            <td class="text-capitalize"><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td class="text-capitalize"><?php echo htmlspecialchars($user['role']); ?></td>
                            <!-- <td>                                
                                <a href="editUser.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="deleteUser.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>                                                        -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center">No account users found.</p>
        <?php endif; ?>
    </div>


</main>

<!-- SweetAlert script -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // Check if there's a status query parameter in the URL
    const urlParams = new URLSearchParams(window.location.search);
    const status = urlParams.get('status');

    if (status === 'deleted') {
        // Show success SweetAlert for user deletion
        Swal.fire({
            icon: 'success',
            title: 'User account deleted!',
            showConfirmButton: false,
            timer: 4000
        });
    } else if (status === 'error') {
        // Show error SweetAlert for deletion failure
        Swal.fire({
            icon: 'error',
            title: 'Failed to delete user!',
            text: 'Please try again.',
            showConfirmButton: true
        });
    } else if (status === 'invalid') {
        // Show error SweetAlert for invalid user ID
        Swal.fire({
            icon: 'error',
            title: 'Invalid user ID!',
            text: 'The specified user could not be found.',
            showConfirmButton: true
        });
    }
</script>

<!-- Footer -->
<?php include './includes/footer.php'; ?>