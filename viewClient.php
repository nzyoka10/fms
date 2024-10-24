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
        <h4 class="text-muted text-capitalize mt-2">Client Record</h4>

        <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item active">Clients</li>
            <li class="breadcrumb-item mb-0">
                <a class="text-decoration-none hover-underline" href="viewClient.php?id=<?php echo $client['id']; ?>">View</a>
            </li>
        </ol>


        <!-- Client Details Card -->
        <div class="card mb-4">
            <div class="card-header d-flex align-items-center">
                <span data-feather="info" class="me-2"></span>View client information
            </div>
            <div class="card-body">

                <!-- first row -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Created At</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['created_at']); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Client Name</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['client_name']); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Email Address</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['client_email']); ?></p>
                    </div>
                </div>

                <!-- second row -->
                <div class="row">
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Mobile Number</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['client_phone']); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Address</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['client_address']); ?></p>
                    </div>
                    <!-- <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Last updated date</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['updated_at']); ?></p>
                    </div> -->
                </div>

                <!-- third row -->
                <div class="row">
                    <h6 class="text-capitalize text-danger p-2">deceased information</h6>

                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Full Name</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['deceased_name']); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Cause</h5>
                        <p class="text-muted text-capitalize"><?php echo htmlspecialchars($client['deceased_cause']); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Date of Death</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['deceased_date_of_death']); ?></p>
                    </div>
                </div>

                <!-- fourth row -->
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Gender</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['deceased_gender']); ?></p>
                    </div>
                    <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Age</h5>
                        <p class="text-muted text-capitalize"><?php echo htmlspecialchars($client['deceased_age']); ?></p>
                    </div>
                    <!-- <div class="col-md-4 mb-3">
                        <h5 class="fw-bold">Date of Death</h5>
                        <p class="text-muted"><?php echo htmlspecialchars($client['deceased_date_of_death']); ?></p>
                    </div> -->
                </div>

            </div>

            <div class="card-footer d-flex justify-content-between align-items-center">
                <!-- Back button -->
                <a href="clients.php" class="btn btn-sm btn-outline-dark d-flex align-items-center">
                    <span data-feather="arrow-left" class="me-1"></span> Back to List
                </a>

                <!-- Edit and Delete buttons -->
                <div class="d-flex">
                    <a href="editClient.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-outline-success me-2 d-flex align-items-center">
                        <span data-feather="edit" class="me-1"></span> Edit
                    </a>

                    <a href="deleteLog.php?id=<?php echo $logistic['id']; ?>" class="btn btn-sm btn-outline-danger d-flex align-items-center"
                        onclick="return confirm('Are you sure you want to delete this record?');">
                        <span data-feather="trash-2" class="me-1"></span> Delete
                    </a>
                </div>
            </div>

        </div>



    </div>




    <?php include './includes/footer.php'; ?>