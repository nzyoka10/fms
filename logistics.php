<?php
// Include functions file
include 'includes/functions.php';

// Fetch logistics data
$logistics = getLogisticsData($conn);

// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="mt-4">Listings of Logistics</h4>
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard / Logistics</li>
    </ol>

    <!-- Schedule Logistics Button -->
    <div class="mb-3">
        <a href="scheduleLogistics.php" class="btn btn-sm btn-success">
            <i class="fas fa-plus"></i> Schedule
        </a>
    </div>

    <!-- Table to display logistics bookings -->
    <div class="table-responsive">
        <?php if (!empty($logistics)): ?>
            <table class="table table-striped table-hover mt-4" id="logisticsTable">
                <thead>
                    <tr>
                        <th>Sn#</th>
                        <!-- <th>Date</th> -->
                        <th>Client Name</th>
                        <!-- <th>Pickup Location</th> -->
                        <th>Destination</th>
                        <th>Vehicle type</th>
                        <th>Status</th>
                        <th>Schedule Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logistics as $index => $logistic): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($logistic['client_name']); ?></td>
                            <!-- <td><?php echo htmlspecialchars($logistic['pickup_location']); ?></td> -->
                            <td><?php echo htmlspecialchars($logistic['destination']); ?></td>
                            <td><?php echo htmlspecialchars($logistic['vehicle']); ?></td>
                            <td><?php echo htmlspecialchars($logistic['status']); ?></td>
                            <td><?php echo htmlspecialchars($logistic['pickup_date']); ?></td>
                            <td>
                                <a href="editLogistic.php?id=<?php echo $logistic['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
                                <a href="deleteLogistic.php?id=<?php echo $logistic['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this logistic record?');">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="text-center text-danger">
                <strong>No logistics data found.</strong>
            </p>
        <?php endif; ?>
    </div>
</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>

<!-- SweetAlert for notifications -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// Handle SweetAlert notifications
const urlParams = new URLSearchParams(window.location.search);
const status = urlParams.get('status');

if (status === 'deleted') {
    Swal.fire({
        icon: 'success',
        title: 'Logistic record deleted successfully!',
        showConfirmButton: false,
        timer: 2000
    });
} else if (status === 'error') {
    Swal.fire({
        icon: 'error',
        title: 'Failed to delete logistic record!',
        text: 'Please try again.',
        showConfirmButton: true
    });
} else if (status === 'invalid') {
    Swal.fire({
        icon: 'error',
        title: 'Invalid logistic record!',
        text: 'The specified record could not be found.',
        showConfirmButton: true
    });
}
</script>
