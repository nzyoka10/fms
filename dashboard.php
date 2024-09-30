<?php

// include the html sections
include './includes/header.php';
include './includes/sidebar.php';
include './includes/title.php';
?>






<!-- Dashboard cards -->
<div class="row g-4">
    <!-- Clients card -->
    <div class="col-md-3">
        <div class="card text-center p-4 shadow-sm">
            <div class="card-body d-flex flex-column align-items-center">
                <div class="card-icon mb-3">
                    <span data-feather="users" style="width: 20px; height: 20px;"></span>
                </div>
                <h5 class="card-title text-muted">Clients</h5>
                <p class="card-text"><strong><?php echo $totalClients; ?></strong></p>
            </div>
        </div>
    </div>

    <!-- Bookings card -->
    <div class="col-md-3">
        <div class="card text-center p-4 shadow-sm">
            <div class="card-body d-flex flex-column align-items-center">
                <div class="card-icon mb-3">
                    <span data-feather="bookmark" style="width: 20px; height: 20px;"></span>
                </div>
                <h5 class="card-title text-muted">Bookings</h5>
                <p class="card-text"><strong><?php echo $totalBookings; ?></strong></p>
            </div>
        </div>
    </div>

    <!-- Pending Tasks card -->
    <div class="col-md-3">
        <div class="card text-center p-4 shadow-sm">
            <div class="card-body d-flex flex-column align-items-center">
                <div class="card-icon mb-3">
                    <span data-feather="briefcase" style="width: 20px; height: 20px;"></span>
                </div>
                <h5 class="card-title text-muted">Pending Tasks</h5>
                <p class="card-text">
                    <strong><?php echo $pendingTasksCount; ?></strong>
                </p>
            </div>
        </div>
    </div>

    <!-- Revenue card -->
    <div class="col-md-3">
        <div class="card text-center p-4 shadow-sm">
            <div class="card-body d-flex flex-column align-items-center">
                <div class="card-icon mb-3">
                    <span data-feather="dollar-sign" style="width: 20px; height: 20px;"></span>
                </div>
                <h5 class="card-title text-muted">Revenue</h5>
                <p class="card-text">
                    <strong>Kes.&nbsp;</strong><?php echo number_format($totalRevenue, 2); ?>
                </p>
            </div>
        </div>
    </div>
</div>


<!-- Additional dashboard content -->
<div class="row g-4 mt-2">
    <div class="col-md-6">
        <div class="card bg-dark text-white">
            <div class="card-body p-4">
                <h4 class="card-title">Welcome to the FMS Dashboard</h4>
                <p class="card-text">
                    This dashboard provides an overview of your funeral management operations, including logistics, inventory, and financial summaries. Use the sidebar to navigate through different sections like client information, scheduling, and reports.
                </p>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning text-dark">
            <div class="card-body p-4">
                <h4 class="card-title">Upcoming Tasks</h4>
                <p class="card-text">
                    Stay on top of your pending tasks such as upcoming funerals, body pickups, inventory restocking, and scheduled client meetings. Ensure smooth operations by keeping track of deadlines and service requests.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Dashboard Statistics -->
<!-- <div class="row g-4 mt-4">
    <div class="col-md-4">
        <div class="card bg-primary text-white">
            <div class="card-body p-4">
                <h4 class="card-title">Total Revenue</h4>
                <p class="card-text">Ksh <?php echo number_format($totalRevenue); ?></p>
                <small class="text-white-50">Revenue generated from completed bookings</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-success text-white">
            <div class="card-body p-4">
                <h4 class="card-title">Completed Services</h4>
                <p class="card-text"><?php echo $completedServicesCount; ?></p>
                <small class="text-white-50">Total number of funerals and services completed</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card bg-danger text-white">
            <div class="card-body p-4">
                <h4 class="card-title">Pending Tasks</h4>
                <p class="card-text"><?php echo $pendingTasksCount; ?></p>
                <small class="text-white-50">Tasks like body pickups, client meetings, and funerals yet to be completed</small>
            </div>
        </div>
    </div>
</div> -->



<?php include './includes/footer.php'; ?>