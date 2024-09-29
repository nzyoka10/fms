<?php

// include the html sections
include './includes/header.php';
include './includes/sidebar.php';
include './includes/title.php';
?>






<!-- Dashboard cards -->
<div class="row g-4 mt-2">
    <!-- Clients card -->
    <div class="col-md-3">
        <div class="card text-center p-3 shadow-sm">
            <div class="card-body">
                <div class="card-icon mb-3">
                    <span data-feather="users"></span>
                </div>
                <h5 class="card-title text-muted">Clients</h5>
                <p class="card-text"><strong><?php echo $totalClients; ?></strong></p>
            </div>
        </div>
    </div>

    <!-- Bookings card -->
    <div class="col-md-3">
        <div class="card text-center p-3 shadow-sm">
            <div class="card-body">
                <div class="card-icon mb-3">
                    <span data-feather="bookmark"></span>
                </div>
                <h5 class="card-title text-muted">Bookings</h5>
                <p class="card-text"><strong><?php echo $totalBookings; ?></strong></p>
            </div>
        </div>
    </div>

    <!-- Inventory card -->
    <div class="col-md-3">
        <div class="card text-center p-3 shadow-sm">
            <div class="card-body">
                <div class="card-icon mb-3">
                    <span data-feather="database"></span>
                </div>
                <h5 class="card-title text-muted">Inventory</h5>
                <p class="card-text"><strong>0</strong></p>
            </div>
        </div>
    </div>

    <!-- Revenue card -->
    <div class="col-md-3">
        <div class="card text-center p-3 shadow-sm">
            <div class="card-body">
                <div class="card-icon mb-3">
                    <span data-feather="dollar-sign"></span>
                </div>
                <h5 class="card-title text-muted">Revenue</h5>
                <p class="card-text"><strong>Kes.&nbsp;</strong>0.0</p>
            </div>
        </div>
    </div>
</div>

<!-- Additional dashboard content -->
<div class="row g-4 mt-2">
    <div class="col-md-6">
        <div class="card bg-dark text-white p-5">
            <h4 class="card-title">Welcome to the Dashboard</h4>
            <p class="card-body">
                This is a brief overview of your current operations and statistics. Use the sidebar to navigate through different sections of the application.
            </p>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card bg-warning text-dark p-5">
            <h4 class="card-title">Upcoming Tasks</h4>
            <p class="card-body">
                Keep track of your pending tasks and important reminders to ensure smooth operations.
            </p>
        </div>
    </div>
</div>

<?php include './includes/footer.php'; ?>