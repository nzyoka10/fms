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
    <h4 class="text-muted text-uppercase pt-2">All Reports</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="reports.php">Reports</a>
        </li>
    </ol>

    <div class="row mt-2">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title">Genarate Report</h5>
                </div>

                <!-- card body -->
                <div class="card-body">
                    
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>