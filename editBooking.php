<?php

// Include functions file and start session if needed
include 'includes/functions.php';

// Initialize response messages
$responseMessage = [];

// Check if the booking ID is set in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Fetch the existing booking details from the database
    $booking = getBookingById($bookingId, $conn);
    if (!$booking) {
        // Show an error if booking not found
        $responseMessage['error'] = 'Booking not found.';
    }
} else {
    // Show an error if no booking ID is provided
    $responseMessage['error'] = 'Invalid booking ID.';
}

// Handle form submission for updating a booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Extract the necessary data from the POST request
    $client_id = $_POST['client_id'] ?? null; // Using null coalescing to handle unset indices
    $service_type = $_POST['service_type'] ?? null;
    $schedule_date = $_POST['schedule_date'] ?? null;
    $vehicle_type = $_POST['vehicle_type'] ?? null;
    $request = $_POST['request'] ?? '';
    $status = $_POST['status'] ?? null;

    // Call the function to handle the booking update
    $updateResponse = updateBooking($bookingId, $client_id, $service_type, $schedule_date, $vehicle_type, $request, $status, $conn);

    // Capture success or error message from the update response
    if (isset($updateResponse['success'])) {
        $responseMessage['success'] = 'Booking updated successfully!';
    } elseif (isset($updateResponse['error'])) {
        $responseMessage['error'] = 'Failed to update booking. Please try again.';
    }
}


// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>


<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="mt-2">Edit Booking Record</h4>

    <ol class="breadcrumb mb-4 pt-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./bookings.php">Bookings</a>
        </li>
    </ol>

    <!-- Display success or error messages if they exist -->
    <?php
        if (!empty($responseMessage)) {
            if (isset($responseMessage['success'])) {
                echo "<div class='alert alert-success mt-2'>{$responseMessage['success']}</div>";
            }
            if (isset($responseMessage['error'])) {
                echo "<div class='alert alert-danger mt-2'>{$responseMessage['error']}</div>";
            }
        }
    ?>

    <!-- Booking Update Form -->
    <form action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $bookingId; ?>" method="post">
        <div class="row">
            <div class="col-md-6 mb-3" hidden>
                <select class="form-select" id="client_id" name="client_id" required>
                    <option value="" disabled>Select client</option>
                    <?php
                    // Fetch clients from the database and populate options
                    $query = "SELECT id, client_id FROM bookings";
                    $result = $conn->query($query);
                    if ($result) {
                        while ($row = $result->fetch_assoc()) {
                            $selected = ($row['id'] == $booking['client_id']) ? 'selected' : '';
                            echo "<option value='{$row['client_id']}' {$selected}>{$row['client_id']}</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <!-- Client Selection Field -->
            <div class="col-md-6 mb-3">
                <label for="deceased_name" class="form-label">Client Name<span class="text-danger">*</span></label>
                <input type="text" class="form-control custom-date" id="deceased_name" name="deceased_name" value="<?php echo htmlspecialchars($booking['deceased_name']); ?>" required>
                </select>
            </div>

            <!-- Service Type Radio Buttons -->
            <div class="col-md-6 mb-3">
                <legend class="col-form-label col-sm-10 pt-2">Service Type<span class="text-danger">*</span></legend>
                <div class="col">
                    <input class="form-check-input" type="radio" name="service_type" id="burial" value="burial" required <?php echo ($booking['service_type'] == 'burial') ? 'checked' : ''; ?>>
                    <label class="form-check-label me-2" for="burial">Burial</label>

                    <input class="form-check-input" type="radio" name="service_type" id="cremation" value="cremation" required <?php echo ($booking['service_type'] == 'cremation') ? 'checked' : ''; ?>>
                    <label class="form-check-label me-2" for="cremation">Cremation</label>

                    <input class="form-check-input" type="radio" name="service_type" id="other" value="other" required <?php echo ($booking['service_type'] == 'other') ? 'checked' : ''; ?>>
                    <label class="form-check-label me-2" for="other">Other</label>
                </div>
            </div>

            <!-- Schedule Date Field -->
            <div class="col-md-6 mb-3">
                <label for="schedule_date" class="form-label">Booking Date<span class="text-danger">*</span></label>
                <input type="date" class="form-control custom-date" id="schedule_date" name="schedule_date" value="<?php echo htmlspecialchars($booking['schedule_date']); ?>" required>
            </div>

            <!-- Vehicle Type Selection -->
            <div class="col-md-6 mb-3">
                <label for="vehicle_type" class="form-label">Vehicle Type<span class="text-danger">*</span></label>
                <select class="form-select" id="vehicle_type" name="vehicle_type" required>
                    <option value="" disabled>Select vehicle</option>
                    <option value="van" <?php echo ($booking['vehicle_type'] == 'van') ? 'selected' : ''; ?>>Van - Body + 6 people</option>
                    <option value="bus" <?php echo ($booking['vehicle_type'] == 'bus') ? 'selected' : ''; ?>>Bus - Body + 30 people</option>
                    <option value="other" <?php echo ($booking['vehicle_type'] == 'other') ? 'selected' : ''; ?>>Other</option>
                </select>
            </div>

            <!-- Additional Requests Field -->
            <div class="col-md-8 mb-3">
                <label for="request" class="form-label">Any other request<span class="text-danger">*</span></label>
                <textarea class="form-control" placeholder="Leave a comment here" id="request" name="request" style="height: 180px"><?php echo htmlspecialchars($booking['request']); ?></textarea>
            </div>

            <!-- Status Field and Buttons -->
            <div class="col-md-4 d-flex flex-column justify-content-between align-items-stretch">
                <div class="mb-2">
                    <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                    <select class="form-select" id="status" name="status" required>
                        <option value="scheduled" <?php echo ($booking['status'] == 'scheduled') ? 'selected' : ''; ?>>Scheduled</option>
                        <option value="completed" <?php echo ($booking['status'] == 'completed') ? 'selected' : ''; ?>>Completed</option>
                    </select>
                </div>

                <div class="d-flex flex-row justify-content-start pt-1">
                    <button type="submit" class="btn btn-sm btn-outline-success mb-3 w-50 d-flex align-items-center justify-content-center">
                        <span class="me-2" data-feather="pocket" style="width: 16px; height: 16px;"></span>
                        Save
                    </button>
                    <a class="btn btn-sm btn-outline-secondary w-50 d-flex align-items-center justify-content-center" href="viewBooking.php?id=<?php echo $booking['id']; ?>">
                        <span class="me-2" data-feather="x-circle" style="width: 16px; height: 16px;"></span>
                        Back to Listing
                    </a>  
                </div>
            </div>
        </div>
    </form>
</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>
