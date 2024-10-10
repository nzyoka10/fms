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
    <h4 class="mt-4">Account Users</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="users.php">All Users</a>
        </li>
    </ol>


    <!-- <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard / Users / Admin</li>
    </ol> -->

    <!-- Table to display account users -->
    <div class="table-responsive">
        <?php if (!empty($users)): ?>
            <table class="table table-bordered table-hover mt-2">
                <thead>
                    <tr>
                        <th>Sn#</th>
                        <th>Date Created</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Email</th>
                        <th>Actions</th> <!-- Actions column -->
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $index => $user): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($user['created_at']); ?></td>
                            <td><?php echo htmlspecialchars($user['username']); ?></td>
                            <td><?php echo htmlspecialchars($user['role']); ?></td> 
                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                            <td>
                                <!-- Actions for edit and delete -->
                                <a href="editUser.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="deleteUser.php?id=<?php echo $user['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                            </td>                                                       
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
