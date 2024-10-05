<?php
// Include functions file and database connection
include 'includes/functions.php';

// Check if the ID is provided
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: logistics.php?status=invalid");
    exit();
}

// Retrieve the ID from the URL
$logistic_id = intval($_GET['id']);

// Fetch the existing logistics data
$logistic = getLogisticById($conn, $logistic_id);

// Check if logistics data was found
if (!$logistic) {
    header("Location: logistics.php?status=invalid");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $client_id = intval($_POST['client_id']);
    $pickup_location = htmlspecialchars($_POST['pickup_location']);
    $destination = htmlspecialchars($_POST['destination']);
    $vehicle = htmlspecialchars($_POST['vehicle']);
    $driver_name = htmlspecialchars($_POST['driver_name']);
    $pickup_date = $_POST['pickup_date'];
    $status = htmlspecialchars($_POST['status']);

    // Update the logistics entry
    $isUpdated = updateLogistic($conn, $logistic_id, $client_id, $vehicle, $driver_name, $pickup_date, $destination, $pickup_location, $status);

    if ($isUpdated) {
        // Redirect with success message
        header("Location: logistics.php?status=updated");
        exit();
    } else {
        // Display error message
        $error = "Failed to update logistics. Please try again.";
    }
}

// Fetch clients with valid bookings to display in the dropdown
$bookings = getValidBookingsWithClients($conn);

// Include header and sidebar
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="mt-4">Edit Logistics</h4>
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item"><a class="text-decoration-none hover-underline" href="logistics.php">Logistics</a></li>
        <li class="breadcrumb-item active">Edit</li>
    </ol>

    <!-- Edit form -->
    <form action="editLogistic.php?id=<?php echo $logistic_id; ?>" method="POST">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="client_id" class="form-label">Client</label>
                <select name="client_id" id="client_id" class="form-select" required>
                    <option value="">Select Client</option>
                    <?php
                    // Populate the dropdown with clients who have valid bookings
                    foreach ($bookings as $booking) {
                        $selected = ($booking['client_id'] == $logistic['client_id']) ? 'selected' : '';
                        echo "<option value='" . $booking['client_id'] . "' $selected>" . htmlspecialchars($booking['client_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="vehicle" class="form-label">Vehicle</label>
                <input type="text" name="vehicle" id="vehicle" class="form-control" placeholder="Vehicle Name" value="<?php echo htmlspecialchars($logistic['vehicle']); ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="pickup_location" class="form-label">Pickup Location</label>
                <input type="text" name="pickup_location" id="pickup_location" class="form-control" placeholder="Enter Pickup Location" value="<?php echo htmlspecialchars($logistic['pickup_location']); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="destination" class="form-label">Destination</label>
                <input type="text" name="destination" id="destination" class="form-control" placeholder="Enter Destination" value="<?php echo htmlspecialchars($logistic['destination']); ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="driver_name" class="form-label">Driver Name</label>
                <input type="text" name="driver_name" id="driver_name" class="form-control" placeholder="Enter Driver's Name" value="<?php echo htmlspecialchars($logistic['driver_name']); ?>" required>
            </div>
            <div class="col-md-6">
                <label for="pickup_date" class="form-label">Pickup Date</label>
                <input type="date" name="pickup_date" id="pickup_date" class="form-control" value="<?php echo htmlspecialchars($logistic['pickup_date']); ?>" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="status" class="form-label">Status</label>
                <select name="status" id="status" class="form-select" required>
                    <option value="pending" <?php echo ($logistic['status'] == 'pending') ? 'selected' : ''; ?>>Pending</option>
                    <option value="completed" <?php echo ($logistic['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                    <option value="canceled" <?php echo ($logistic['status'] == 'canceled') ? 'selected' : ''; ?>>Canceled</option>
                </select>
            </div>
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Update</button>
        </div>
    </form>

    <!-- Error display -->
    <?php if (isset($error)): ?>
        <div class="alert alert-danger mt-3">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>
</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>
