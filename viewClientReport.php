<?php
// Include necessary files
include 'includes/functions.php';  // Assuming the function is inside this file
include './includes/header.php';
include './includes/sidebar.php';

// Assuming you already have a database connection $conn
// Replace this with your actual database connection
// $conn = new mysqli('host', 'username', 'password', 'database');

// Get the client ID from the query string (e.g., viewClientReport.php?clientId=1)
$clientId = isset($_GET['clientId']) ? (int)$_GET['clientId'] : 0;

if ($clientId > 0) {
    // Fetch client data using the function
    $clientData = fetchClientData($conn, $clientId);

    if ($clientData) {
        // Extract data from the returned array
        $client = $clientData['client'];
        $bookings = $clientData['bookings'];
        $services = $clientData['services'];
    } else {
        // Handle error if data fetching fails
        echo "<p class='text-danger'>Error fetching client data.</p>";
        exit;
    }
} else {
    // Handle invalid client ID
    echo "<p class='text-danger'>Invalid client ID.</p>";
    exit;
}
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="text-muted mt-2">Client Report</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./reports.php">Reports</a>
        </li>
    </ol>

    <!-- Display specific client's fetched data record here -->
    <div class="client-details">
        <h5>Client Details</h5>
        <table class="table table-bordered">
            <tr>
                <th>Name</th>
                <td><?php echo htmlspecialchars($client['client_name']); ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?php echo htmlspecialchars($client['client_email']); ?></td>
            </tr>
            <tr>
                <th>Phone</th>
                <td><?php echo htmlspecialchars($client['client_phone']); ?></td>
            </tr>
            <tr>
                <th>Address</th>
                <td><?php echo htmlspecialchars($client['client_address']); ?></td>
            </tr>
            <tr>
                <th>Deceased Name</th>
                <td><?php echo htmlspecialchars($client['deceased_name']); ?></td>
            </tr>
            <tr>
                <th>Deceased Age</th>
                <td><?php echo htmlspecialchars($client['deceased_age']); ?></td>
            </tr>
            <tr>
                <th>Deceased Cause</th>
                <td><?php echo htmlspecialchars($client['deceased_cause']); ?></td>
            </tr>
            <tr>
                <th>Deceased Gender</th>
                <td><?php echo htmlspecialchars($client['deceased_gender']); ?></td>
            </tr>
        </table>
    </div>

    <div class="client-bookings mt-4">
        <h5>Client's Bookings</h5>
        <?php if (!empty($bookings)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Booking ID</th>
                        <th>Service Type</th>
                        <th>Schedule Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($booking['id']); ?></td>
                            <td><?php echo htmlspecialchars($booking['service_type']); ?></td>
                            <td><?php echo htmlspecialchars($booking['schedule_date']); ?></td>
                            <td><?php echo htmlspecialchars($booking['status']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No bookings found for this client.</p>
        <?php endif; ?>
    </div>

    <div class="client-services mt-4">
        <h5>Booking Services</h5>
        <?php if (!empty($services)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Service ID</th>
                        <th>Service Name</th>
                        <th>Quantity</th>
                        <th>Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($services as $service): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($service['service_id']); ?></td>
                            <td><?php echo htmlspecialchars($service['service_name']); ?></td>
                            <td><?php echo htmlspecialchars($service['quantity']); ?></td>
                            <td><?php echo htmlspecialchars($service['total_price']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No services found for this client.</p>
        <?php endif; ?>
    </div>

</main>

<!-- Footer -->
<?php include './includes/footer.php' ?>
