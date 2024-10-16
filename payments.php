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
    <h4 class="text-muted text-uppercase mt-4">Paid Bookings</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="payments.php">Payments</a>
        </li>
    </ol>


    <!-- Table to display processed bookings -->
    <div class="table-responsive">
        <?php if (!empty($processedBookings)): ?>
            <table class="table table-striped mt-2" id="paymentsTable">
                <thead>
                    <tr>
                        <th>Sn#</th>                        
                        <th>Receipt</th>
                        <th>Client Name</th>
                        <th>Booked date</th> 
                        <th>Payment date</th>                       
                        <th>Payment method</th>
                        <th>Total amount</th>
                        <!-- <th>Tax</th> -->
                        <!-- <th>Discount</th> -->
                        
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($processedBookings as $index => $booking): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>                            
                            <td><?php echo htmlspecialchars($booking['receipt_number']); ?></td>
                            <td><?php echo htmlspecialchars($booking['client_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['schedule_date']); ?></td>
                            <td><?php echo htmlspecialchars($booking['payment_date']); ?></td>
                            <td class="text-capitalize"><?php echo htmlspecialchars($booking['payment_method']); ?></td>
                            <td><strong class="text-success me-2">Kes.</strong><?php echo number_format($booking['amount'], 2); ?></td>
                            <!-- <td>Kes. <?php echo number_format($booking['discount'], 2); ?></td> -->
                            <!-- <td><?php echo htmlspecialchars($booking['payment_date']); ?></td> -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-danger">No processed bookings found.</p>
        <?php endif; ?>
    </div>


</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>