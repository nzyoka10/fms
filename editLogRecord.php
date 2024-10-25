<?php
// Include necessary files
include 'includes/functions.php';

// Check if an ID is provided in the URL
if (isset($_GET['id'])) {
    $logistic_id = intval($_GET['id']); // Get logistic ID from URL

    // Fetch the logistic record
    $logistic = getLogisticById($conn, $logistic_id);

    if (!$logistic) {
        header("Location: logistics.php?status=invalid");
        exit();
    }
} else {
    header("Location: logistics.php?status=error");
    exit();
}

// Handle form submission (update record)
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve updated values from the form
    $vehicle = $_POST['vehicle'];
    $driver_name = $_POST['driver_name'];
    $pickup_date = $_POST['pickup_date'];
    $destination = $_POST['destination'];
    $status = $_POST['status'];

    // Update the logistic record
    if (updateLogisticRecord($conn, $logistic_id, $vehicle, $driver_name, $pickup_date, $destination, $status)) {
        header("Location: logistics.php?status=updated");
        exit();
    } else {
        $error = "Failed to update the record. Please try again.";
    }
}

// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main Section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
    <h4>Edit Record</h4>

    <!-- Breadcrumb -->
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a href="./viewLogRecord.php?id=<?php echo $logistic['id']; ?>" class="text-decoration-none hover-underline">Edit record</a>
        </li>
        
    </ol>

    <!-- Form for editing logistic record -->
    <form method="POST" action="">
        <div class="card">
        <div class="card-header d-flex align-items-center">
                <span data-feather="info" class="me-2"></span>
                <h5 class="text-muted text-uppercase"><?php echo htmlspecialchars($logistic['client_name']); ?></h5>
            </div>
            
            <div class="card-body">
                <?php if (isset($error)): ?>
                    <p class="text-danger"><?php echo $error; ?></p>
                <?php endif; ?>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="vehicle_type" class="form-label">Vehicle</label>
                        <input type="text" class="form-control" id="vehicle_type" name="vehicle_type" value="<?php echo htmlspecialchars($logistic['vehicle_type']); ?>" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="deceased_name" class="form-label">Deceased Person</label>
                        <input type="text" class="form-control" id="deceased_name" name="deceased_name" value="<?php echo htmlspecialchars($logistic['deceased_name']); ?>" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="schedule_date" class="form-label">Pickup Date</label>
                        <input type="date" class="form-control" id="schedule_date" name="schedule_date" value="<?php echo htmlspecialchars($logistic['schedule_date']); ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="request" class="form-label">Request</label>
                        <input type="text" class="form-control" id="request" name="request" value="<?php echo htmlspecialchars($logistic['request']); ?>" required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="status" class="form-label">Status</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="Scheduled" <?php echo ($logistic['status'] == 'Scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                            <option value="In Progress" <?php echo ($logistic['status'] == 'In Progress') ? 'selected' : ''; ?>>In Progress</option>
                            <option value="Completed" <?php echo ($logistic['status'] == 'Completed') ? 'selected' : ''; ?>>Completed</option>
                            <option value="Cancelled" <?php echo ($logistic['status'] == 'Cancelled') ? 'selected' : ''; ?>>Cancelled</option>
                        </select>
                    </div>
                </div>

                
            </div>
            <div class="card-footer d-flex justify-content-between">
                <a href="logistics.php" class="btn btn-sm btn-outline-dark">
                    <span data-feather="arrow-left"></span> Back to List
                </a>
                <button type="submit" class="btn btn-sm btn-outline-success">
                    <span data-feather="save"></span> Save Changes
                </button>
            </div>
        </div>
    </form>
</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>

<!-- Feather Icons Script -->
<script>
    feather.replace(); // Initialize feather icons
</script>

<?php
// Close connection if needed
$conn->close();
?>
