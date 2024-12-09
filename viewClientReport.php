<?php
// Include necessary files (database connection, functions, etc.)
include 'includes/functions.php';

// Check if the 'id' parameter is set in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $bookingId = (int)$_GET['id']; // Get the booking ID and cast to an integer

    // Fetch the booking details using a function
    $booking = getBookingById($bookingId);

    // Check if a valid booking was found
    if (!$booking) {
        echo "<p class='text-danger'>Booking not found.</p>";
        exit(); // Stop further execution if the booking isn't found
    }
} else {
    echo "<p class='text-danger'>Invalid booking ID.</p>";
    exit(); // Stop execution if no valid ID is provided
}

// More includes of HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
    <!-- Page heading -->
    <h4 class="mt-4">View Report</h4>

    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./reports.php">Reports</a>
        </li>
    </ol>

    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center">
            <span data-feather="info" class="me-2"></span>Reports
        </div>
        <!-- <div class="card-header">
            <h3 class="card-title">Client: <?php echo htmlspecialchars($booking['client_id']); ?></h3>
        </div> -->
        <div class="card-body">
            <div class="row">
                <!-- Client Name -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold-30">Client Name</h5>
                    <p class="text-muted text-uppercase"><?php echo htmlspecialchars($booking['client_name']); ?></p>
                </div>
                <!-- Service Type -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold-30">Service</h5>
                    <p class="text-muted text-capitalize"><?php echo htmlspecialchars($booking['service_type']); ?></p>
                </div>
            </div>

            <div class="row">
                <!-- Booking Date -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold-30">Booked date</h5>
                    <p class="text-muted"><?php echo htmlspecialchars($booking['schedule_date']); ?></p>
                </div>
                <!-- Location -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold-30">Vehicle</h5>
                    <p class="text-muted text-capitalize"><?php echo htmlspecialchars($booking['vehicle_type']); ?></p>
                </div>
            </div>

            <div class="row">
                <!-- Request Link -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold-30">Request</h5>
                    <p class="text-muted"><?php echo htmlspecialchars($booking['request']); ?></p>
                </div>
                <!-- Status -->
                <div class="col-md-3 mb-3">
                    <h5 class="fw-bold-30">Status</h5>
                    <p class="badge bg-<?php echo ($booking['status'] === 'completed') ? 'success' : 'danger'; ?> p-2">
                        <?php echo htmlspecialchars($booking['status']); ?>
                    </p>
                </div>

                <!-- Booked date -->
                <div class="col-md-3 mb-3">
                    <h5 class="fw-bold text-muted h6">Date</h5>
                    <p class="text-muted"><?php echo htmlspecialchars($booking['schedule_date']); ?></p>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-between align-items-center">
    <!-- Left section: Edit and Pay buttons -->
    <div class="d-flex">        
        <a href="" class="btn btn-sm btn-outline-success me-2 d-flex align-items-center">
            Print&nbsp;<span data-feather="print"></span>
        </a>
        <a href="reports.php" class="btn btn-sm btn-outline-danger me-2 d-flex align-items-center">
            Back&nbsp;<span data-feather="x-circle2"></span>
        </a>
    </div>

   
</div>


        <!-- <div class="card-footer text-end">
            <a href="bookings.php" class="btn btn-outline-secondary">Back to Bookings</a>
        </div> -->
    </div>


</main>

<?php include './includes/footer.php'; ?>