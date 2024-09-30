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
        <h4 class="mt-4">View Bookings</h4>
        <ol class="breadcrumb mb-4">
            <li class="breadcrumb-item active">Dashboard / Booking Information</li>
        </ol>

    <div class="card shadow-sm">
        <div class="card-header d-flex align-items-center">
            <span data-feather="info" class="me-2"></span>Booking Information
        </div>
        <!-- <div class="card-header">
            <h3 class="card-title">Client: <?php echo htmlspecialchars($booking['client_id']); ?></h3>
        </div> -->
        <div class="card-body">
            <div class="row">
                <!-- Client Name -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold">Client name</h5>
                    <p class="text-muted"><?php echo htmlspecialchars($booking['client_name']); ?></p>
                </div>
                <!-- Service Type -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold">Service type</h5>
                    <p class="text-muted text-capitalize"><?php echo htmlspecialchars($booking['service_type']); ?></p>
                </div>
            </div>

            <div class="row">
                <!-- Booking Date -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold">Booked date</h5>
                    <p class="text-muted"><?php echo htmlspecialchars($booking['schedule_date']); ?></p>
                </div>
                <!-- Location -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold">Vehicle type</h5>
                    <p class="text-muted text-capitalize"><?php echo htmlspecialchars($booking['vehicle_type']); ?></p>
                </div>
            </div>

            <div class="row">
                <!-- Google Maps Link -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold">Request</h5>
                    <p class="text-muted"><?php echo htmlspecialchars($booking['request']); ?></p>
                </div>
                <!-- Status -->
                <div class="col-md-6 mb-3">
                    <h5 class="fw-bold">Status</h5>
                    <p class="badge bg-<?php echo ($booking['status'] === 'completed') ? 'success' : 'danger'; ?> p-2">
                        <?php echo htmlspecialchars($booking['status']); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="card-footer d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom-none">

            <!-- edit button -->
            <a href="editBooking.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-primary me-2 d-flex align-items-center">
                Edit&nbsp;<span data-feather="edit-3"></span>
            </a>
            <a href="paybooking.php" class="btn btn-sm btn-success me-2 d-flex align-items-center">
                Pay&nbsp;<span data-feather="send"></span>
            </a>
            <a href="#!" class="btn btn-sm btn-dark me-2 d-flex align-items-center" onclick="return confirm('Are you sure you want to delete this booking record?');">
                Delete&nbsp;<span data-feather="trash-2"></span>
            </a>
            <a href="bookings.php" class="btn btn-sm btn-danger me-2 d-flex align-items-center">
                Back&nbsp;<span data-feather="skip-back"></span>
            </a>
            <!-- <a  class="btn btn-sm btn-success">Payments</a> -->
            <!-- <a href="bookings.php" class="btn btn-sm btn-danger">Back home</a> -->
        </div>

        <!-- <div class="card-footer text-end">
            <a href="bookings.php" class="btn btn-outline-secondary">Back to Bookings</a>
        </div> -->
    </div>


</main>

<?php include './includes/footer.php'; ?>