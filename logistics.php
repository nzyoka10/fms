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
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./logistics.php">Logistics</a>
        </li>
    </ol>


    <!-- <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard / Logistics</li>
    </ol> -->

    <!-- Schedule Logistics Button -->
    <div class="d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-2">
        <a href="scheduleLogistics.php" class="btn btn-sm btn-outline-dark d-flex align-items-center">
            <span data-feather="truck"></span>&nbsp;Schedule Trip
        </a>
    </div>

    <!-- Table to display logistics bookings -->
    <div class="table">
        <?php if (!empty($logistics)): ?>
            <table class="table table-striped table-hover mt-3" id="logisticsTable">
                <thead>
                    <tr>
                        <th>Sn#</th>
                        <th>Booked Vehicle</th>
                        <th>Driver Name</th>
                        <th>Client's Name</th>
                        <th>Booked Date</th>
                        <th>Destination</th>                        
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($logistics as $index => $logistic): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($logistic['vehicle']); ?></td>
                            <td class="text-muted text-uppercase"><?php echo htmlspecialchars($logistic['driver_name']); ?></td>
                            <td class=" text-muted text-uppercase"><?php echo htmlspecialchars($logistic['client_name']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($logistic['pickup_date']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($logistic['destination']); ?></td>                            
                            <td class="d-flex">
                                <a href="editLogRecord.php?id=<?php echo $logistic['id']; ?>" class="btn btn-sm btn-outline-dark me-2">
                                    <span data-feather="edit-3" style="width: 16px; height: 16px; border-radius: 50%;"></span>
                                    <!-- Edit -->
                                </a>
                                <a href="viewLogRecord.php?id=<?php echo $logistic['id']; ?>" class="btn btn-sm btn-outline-success me-2">
                                    <span class="me-2" data-feather="eye" style="width: 16px; height: 16px;"></span>   
                                    <!-- view -->
                                </a>                                
                                <a href="deleteLog.php?id=<?php echo $logistic['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this logistic record?');">
                                    <span data-feather="x-circle" style="width: 16px; height: 16px; border-radius: 50%;"></span>
                                    <!-- Delete -->
                                </a>
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
