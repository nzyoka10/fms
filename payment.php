<?php
// Include functions file
include 'includes/functions.php';

// Initialize response messages
$responseMessage = [];


// Check if the booking ID is set in the URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $bookingId = $_GET['id'];

    // Fetch the existing booking details from the database
    $booking = getBookingById($bookingId); // Removed $conn as it's global
    if (!$booking) {
        // Redirect or show an error if booking not found
        $responseMessage['error'] = 'Booking not found.';
    }
} else {
    // Redirect or show an error if no booking ID is provided
    $responseMessage['error'] = 'Invalid booking ID.';
}

// Handle form submission for processing payment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($bookingId)) {
    // Pass the booking ID to the payment handling function
    $responseMessage = handlePaymentForm($_POST, $conn, $bookingId);
}


// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="mt-4">Make Payment</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="payment.php?id=<?php echo $booking['id']; ?>">Payments</a>
        </li>
    </ol>


    <!-- <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Dashboard / Payment</li>
    </ol> -->

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

    <!-- Payment Form -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?id=' . $bookingId; ?>" method="post">
    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
    <div class="row">
        <div class="col-md-6 mb-3">
            <label for="receipt_number" class="form-label">Receipt Number<span class="text-danger">*</span></label>
            <input type="text" class="form-control" id="receipt_number" name="receipt_number" required readonly>
        </div>
        <div class="col-md-6 mb-3">
            <label for="payment_method" class="form-label">Payment Method<span class="text-danger">*</span></label>
            <select class="form-select" id="payment_method" name="payment_method" required>
                <option value="" disabled selected>Select payment method</option>
                <option value="cash">Cash</option>
                <option value="mpesa">M-Pesa</option>
            </select>
        </div>
        <div class="col-md-6 mb-3">
            <label for="amount" class="form-label">Amount (Ksh)<span class="text-danger">*</span></label>
            <input type="number" class="form-control" id="amount" name="amount" required>
        </div>
        <div class="col-md-6 mb-3">
            <label for="tax" class="form-label">Tax (Ksh)</label>
            <input type="number" class="form-control" id="tax" name="tax" value="0" min="0">
        </div>
        <div class="col-md-6 mb-3">
            <label for="discount" class="form-label">Discount (Ksh)</label>
            <input type="number" class="form-control" id="discount" name="discount" value="0" min="0">
        </div>
        <div class="card-footer d-flex justify-content-start align-items-center pt-3 pb-2 mb-4 border-bottom-none">
            <button type="submit" class="btn btn-sm btn-primary me-2"> Pay&nbsp;<span data-feather="send"></span></button>
            <button type="reset" class="btn btn-sm btn-dark me-2">Reset&nbsp;<span data-feather="refresh-cw"></span></button>                
            <a class="btn btn-sm btn-danger" href="viewBooking.php?id=<?php echo $booking['id']; ?>">
                Back&nbsp;<span data-feather="skip-back"></span>
            </a>                
        </div>
    </div>
</form>


    
</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>
