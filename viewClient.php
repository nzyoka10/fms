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

        <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item active">Dashboard</li>
            <li class="breadcrumb-item mb-3">
                <a class="text-decoration-none hover-underline" href="./clients.php">Clients</a>
            </li>
        </ol>


        <!-- <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard / View Client</li>
        </ol> -->

        <!-- Client Details Card -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center">
                <span data-feather="info" class="me-2"></span>Client Information
            </div>
            <div class="card-body">

                <!-- first row -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Created At</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['created_at']); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Client Names</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['full_name']); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Email Address</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['email']); ?></p>
                    </div>
                </div>

                <!-- second row -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Mobile Number</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['phone']); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Address</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['address']); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Last updated date</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['updated_at']); ?></p>
                    </div>
                </div>

            </div>
            <div class="card-footer d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom-none">
                <a href="editClient.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-outline-success me-2 d-flex align-items-center">
                    Edit&nbsp;<span data-feather="edit-3"></span>
                </a>
                <a href="#!" class="btn btn-sm btn-outline-dark me-2 d-flex align-items-center" onclick="return confirm('Are you sure you want to delete this clients record?');">
                    Delete&nbsp;<span data-feather="trash-2"></span>
                </a>
                <a href="clients.php" class="btn btn-sm btn-outline-danger me-2 d-flex align-items-center">
                    Go Back&nbsp;<span data-feather="x-circle"></span>
                </a>
                
                <!-- <a href="editClient.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-primary">Edit record</a> -->
                <!-- <a href="deleteClient.php?id=<?php echo $client['id']; ?>" 
                 class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this client?');">Delete Client</a> -->
                <!-- <a href="clients.php" class="btn btn-sm btn-danger">Back home</a> -->
            </div>
        </div>
    </div>




    <?php include './includes/footer.php'; ?>