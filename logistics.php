<?php
// Include functions file
include 'includes/functions.php';

// Get the processed bookings
$processedBookings = getProcessedBookings($conn);

// get booking too
$bookings = getBookings();

// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="mt-4">Listings of logistics</h4>
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard / Logistics</li>
    </ol>










</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>