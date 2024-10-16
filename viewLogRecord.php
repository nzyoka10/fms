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
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./logistics.php">Logistics</a>
        </li>
        <li class="breadcrumb-item active">View Logistic Record</li>
    </ol>

    <!-- Logistic Record Details -->
    <div class="card">
        <div class="card-header">
            <h5>Details for Logistic #<?php echo htmlspecialchars($logistic['id']); ?></h5>
        </div>
        <div class="card-body">
            <table class="table table-borderless">
                <tr>
                    <th>Client Name:</th>
                    <td><?php echo htmlspecialchars($logistic['client_name']); ?></td>
                </tr>
                <tr>
                    <th>Vehicle:</th>
                    <td><?php echo htmlspecialchars($logistic['vehicle']); ?></td>
                </tr>
                <tr>
                    <th>Driver Name:</th>
                    <td><?php echo htmlspecialchars($logistic['driver_name']); ?></td>
                </tr>
                <tr>
                    <th>Pickup Location:</th>
                    <td><?php echo htmlspecialchars($logistic['pickup_location']); ?></td>
                </tr>
                <tr>
                    <th>Destination:</th>
                    <td><?php echo htmlspecialchars($logistic['destination']); ?></td>
                </tr>
                <tr>
                    <th>Pickup Date:</th>
                    <td><?php echo htmlspecialchars($logistic['pickup_date']); ?></td>
                </tr>
                <tr>
                    <th>Status:</th>
                    <td><?php echo htmlspecialchars($logistic['status']); ?></td>
                </tr>
                <tr>
                    <th>Created At:</th>
                    <td><?php echo htmlspecialchars($logistic['created_at']); ?></td>
                </tr>
                <tr>
                    <th>Last Updated:</th>
                    <td><?php echo htmlspecialchars($logistic['updated_at']); ?></td>
                </tr>
            </table>
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

