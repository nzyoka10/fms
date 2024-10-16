<?php
// Include functions and database connection
include 'includes/functions.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize and retrieve form data
    $client_id = intval($_POST['client_id']); // Client ID from dropdown
    $pickup_location = htmlspecialchars($_POST['pickup_location']); // Pickup location
    $destination = htmlspecialchars($_POST['destination']); // Destination
    $vehicle = htmlspecialchars($_POST['vehicle']); // Vehicle name
    $driver_name = htmlspecialchars($_POST['driver_name']); // Driver's name
    $pickup_date = $_POST['pickup_date']; // Pickup date
    
    // Schedule the logistics entry
    $isScheduled = scheduleLogistic($conn, $client_id, $pickup_location, $destination, $vehicle, $driver_name, $pickup_date);

    if ($isScheduled) {
        // Redirect with success message if scheduling is successful
        header("Location: logistics.php?status=scheduled");
        exit();
    } else {
        // Display error message if scheduling fails
        $error = "Failed to schedule logistics. Please try again.";
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
    <h4 class="mt-4">Schedule Transport</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./logistics.php">Logistics</a>
        </li>
    </ol>


    <!-- <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard / Logistics</li>
    </ol> -->

    <!-- Schedule form -->
    <form action="scheduleLogistics.php" method="POST">
        <div class="row mb-3">
            <div class="col-md-6">
                <label for="client_id" class="form-label">Client</label>
                <select name="client_id" id="client_id" class="form-select" required>
                    <option value="">Select Client</option>
                    <?php
                    // Populate the dropdown with clients who have valid bookings
                    foreach ($bookings as $booking) {
                        echo "<option value='" . $booking['client_id'] . "'>" . htmlspecialchars($booking['client_name']) . "</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="vehicle" class="form-label">Vehicle</label>
                <input type="text" name="vehicle" id="vehicle" class="form-control" placeholder="Vehicle Name" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="driver_name" class="form-label">Driver Name</label>
                <input type="text" name="driver_name" id="driver_name" class="form-control" placeholder="Enter Driver's Name" required>
            </div> 
            <div class="col-md-6">
                <label for="pickup_date" class="form-label">Pickup Date</label>
                <input type="date" name="pickup_date" id="pickup_date" class="form-control" required>
            </div>                      
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="destination" class="form-label">Destination</label>
                <input type="text" name="destination" id="destination" class="form-control" placeholder="Enter Destination" required>
            </div>
            <div class="col-md-6">
                <label for="pickup_location" class="form-label">Pickup Location</label>
                <input type="text" name="pickup_location" id="pickup_location" class="form-control" placeholder="Enter Pickup Location" required>
            </div>            
        </div>

        <div class="text-end">
            <button type="submit" class="btn btn-success">Schedule</button>
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
