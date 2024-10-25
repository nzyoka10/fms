<?php
// Include the database connection and necessary functions
include 'includes/functions.php';

// Check if an ID is passed in the URL
if (isset($_GET['id'])) {
    $logistic_id = intval($_GET['id']); // Get the logistic record ID and cast to an integer for safety

    // Fetch the logistic record by ID
    $logistic = getLogisticById($conn, $logistic_id);

    // Check if the logistic record was found
    if (!$logistic) {
        header("Location: logistics.php?status=invalid");
        exit();
    }
} else {
    // Redirect if no ID is provided
    header("Location: logistics.php?status=error");
    exit();
}

// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main Section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
    <h4 class="mb-4">Transport Record</h4>

    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./logistics.php">View</a>
        </li>
        
    </ol>

    <!-- Logistic Record Details -->
    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center">
            <span data-feather="info" class="me-2"></span><?php echo htmlspecialchars($logistic['client_name']); ?>
        </div>
        <div class="card-body">

            <div class="row">
                <!-- Client Name -->
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold-30">Client</h5>
                    <p class="text-muted text-uppercase"><?php echo htmlspecialchars($logistic['client_name']); ?></p>
                </div>
                <!-- vehicle Type -->
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold-30">Vehicle</h5>
                    <p class="text-muted text-capitalize"><?php echo htmlspecialchars($logistic['vehicle_type']); ?></p>
                </div>
                <!-- Service Type -->
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold-30">Deceased</h5>
                    <p class="text-muted text-capitalize"><?php echo htmlspecialchars($logistic['deceased_name']); ?></p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold-30">Service</h5>
                    <p class="text-muted text-capitalize"><?php echo htmlspecialchars($logistic['service_type']); ?></p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold-30">Request</h5>
                    <p class="text-muted text-capitalize"><?php echo htmlspecialchars($logistic['request']); ?></p>
                </div>
                <div class="col-md-4 mb-3">
                    <h5 class="fw-bold-30">Booked date</h5>
                    <p class="text-muted text-capitalize"><?php echo htmlspecialchars($logistic['schedule_date']); ?></p>
                </div>
            </div>
          
        </div>
        
        <div class="card-footer d-flex justify-content-between">
            <a href="logistics.php" class="btn btn-sm btn-outline-dark">
                <span data-feather="arrow-left"></span> Back to List
            </a>


            <div>
                <a href="editLogRecord.php?id=<?php echo $logistic['id']; ?>" class="btn btn-sm btn-outline-primary me-2">
                    <span data-feather="edit"></span> Edit
                </a>
                <a href="deleteLog.php?id=<?php echo $logistic['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this record?');">
                    <span data-feather="trash-2"></span> Delete
                </a>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>

<!-- Feather Icons Script -->
<script>
    feather.replace(); // Initializes feather icons
</script>

