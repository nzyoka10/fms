<?php
// include the html sections
include './includes/header.php';
include './includes/sidebar.php';
include './includes/title.php';

// Ensure variables are initialized or fetched here
// Example:
// $totalClients = 150;
// $totalBookings = 50;
// $pendingTasksCount = 30;
// $totalRevenue = 100000.00;
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
                    <span data-feather="truck" style="width: 20px; height: 20px;"></span>
                </div>
                <h5 class="card-title text-muted">Pending</h5>
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
    
    <!-- Pie Chart Card -->
    <div class="col-md-6">
        <div class="card bg-light text-dark">
            <div class="card-body p-4">
                <h4 class="card-title">Task Distribution</h4>
                <canvas id="taskDistributionChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Recent Activities Card -->
    <div class="col-md-6">
        <div class="card bg-light">
            <div class="card-body p-4">
                <h4 class="card-title">Recent Activities</h4>
                <ul class="list-group">
                    <li class="list-group-item">Client John Doe's body pickup scheduled</li>
                    <li class="list-group-item">Inventory restocking completed for coffins</li>
                    <li class="list-group-item">Scheduled client meeting with Jane Smith</li>
                    <li class="list-group-item">Upcoming funeral for client Alice Brown</li>
                    <li class="list-group-item">Body cremation request received for Mark Lee</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart Data
    const data = {
        labels: ['Funerals Completed', 'Upcoming Funerals', 'Inventory Restocking', 'Scheduled Meetings'],
        datasets: [{
            data: [45, 25, 15, 15],  // Dummy data for task distribution
            backgroundColor: ['#36A2EB', '#FF5733', '#4CAF50', '#FFEB3B'], // Chart colors
            hoverOffset: 4
        }]
    };

    // Pie Chart Configuration
    const config = {
        type: 'pie',
        data: data,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(tooltipItem) {
                            return tooltipItem.label + ': ' + tooltipItem.raw + '%'; // Adding percentage to the tooltip
                        }
                    }
                }
            }
        },
    };

    // Render Pie Chart
    window.onload = function () {
        const ctx = document.getElementById('taskDistributionChart').getContext('2d');
        if (ctx) {
            new Chart(ctx, config);
        } else {
            console.error('Chart.js canvas context not found.');
        }
    };
</script>

<?php include './includes/footer.php'; ?>
