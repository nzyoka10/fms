<?php
// Start the session
// session_start();

// Include functions file
include 'includes/functions.php';

// Check if a client ID is provided in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $clientId = $_GET['id'];

    // Fetch the client details by ID
    $client = getClientById($clientId);

    // Check if the client exists
    if (!$client) {
        header("Location: dashboard.php?error=ClientNotFound");
        exit();
    }
} else {
    // If no ID is provided or ID is invalid, redirect back to the dashboard
    header("Location: dashboard.php?error=InvalidClientId");
    exit();
}

// more includes of html templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">

    <div class="container-fluid px-4">
        <!-- Page heading -->
        <h4 class="mt-4">View client</h4>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard / View Client</li>
        </ol>

        <!-- Client Details Card -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center">
                <span data-feather="info" class="me-2"></span>Client Information
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Create at</th>
                        <td><?php echo $client['created_at']; ?></td>
                    </tr>                    
                    <tr>
                        <th>Full name</th>
                        <td><?php echo $client['full_name']; ?></td>
                    </tr>
                    <tr>
                        <th>Email address</th>
                        <td><?php echo $client['email']; ?></td>
                    </tr>
                    <tr>
                        <th>Mobile number</th>
                        <td><?php echo $client['phone']; ?></td>
                    </tr>
                    <tr>
                        <th>Address</th>
                        <td><?php echo $client['address']; ?></td>
                    </tr>
                    <tr>
                        <th>Updated date</th>
                        <td><?php echo $client['updated_at']; ?></td>
                    </tr>
                </table>
            </div>
            <div class="card-footer">
                <a href="editClient.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-primary">Edit record</a>
                <!-- <a href="deleteClient.php?id=<?php echo $client['id']; ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this client?');">Delete Client</a> -->
                <a href="clients.php" class="btn btn-sm btn-danger">Back home</a>
            </div>
        </div>
    </div>




    <?php include './includes/footer.php'; ?>