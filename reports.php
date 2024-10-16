<?php

// Include necessary files
include 'includes/functions.php';
include './includes/header.php';
include './includes/sidebar.php';
require './vendor/autoload.php'; // Fixed missing semicolon

// Import the Dompdf class here, at the top of the file
use Dompdf\Dompdf;

// Initialize variables
$logisticsData = fetchLogisticsData($conn); 
$error = '';
$filter = '';

// Check if the form is submitted
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    // Fetch filtered logistics data based on the selected filter
    if ($filter == 'week') {
        $logisticsData = getLogisticsDataByWeek($conn); // Pass the connection
    } elseif ($filter == 'month') {
        $logisticsData = getLogisticsDataByMonth($conn); // Pass the connection
    } elseif ($filter == 'range') {
        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';

        if (!empty($startDate) && !empty($endDate)) {
            $logisticsData = getLogisticsDataByRange($conn, $startDate, $endDate); // Pass the connection
        } else {
            $error = 'Please select a valid date range.';
        }
    } else {
        $error = 'Invalid filter option selected.';
    }
}

// Handle Export Requests
if (isset($_GET['export'])) {
    if ($_GET['export'] == 'pdf') {
        exportToPDF($logisticsData); // Function to export to PDF
    } elseif ($_GET['export'] == 'excel') {
        exportToExcel($logisticsData); // Function to export to Excel
    }
}

// PDF Export Function
function exportToPDF($logisticsData) {
    // Import the Dompdf class
    // use Dompdf\Dompdf; 

    // Create an instance of Dompdf
    $dompdf = new Dompdf();

    // Generate the HTML content
    $html = '<h1>Logistics Report</h1>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">';
    $html .= '<thead><tr><th>Sn#</th><th>Client Name</th><th>Pickup Location</th><th>Destination</th><th>Vehicle</th><th>Status</th><th>Pickup Date</th></tr></thead>';
    $html .= '<tbody>';

    // Populate the table with data
    foreach ($logisticsData as $index => $logistic) {
        $html .= '<tr>';
        $html .= '<td>' . ($index + 1) . '</td>';
        $html .= '<td>' . htmlspecialchars($logistic['client_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($logistic['pickup_location']) . '</td>';
        $html .= '<td>' . htmlspecialchars($logistic['destination']) . '</td>';
        $html .= '<td>' . htmlspecialchars($logistic['vehicle']) . '</td>';
        $html .= '<td>' . htmlspecialchars($logistic['status']) . '</td>';
        $html .= '<td>' . htmlspecialchars($logistic['pickup_date']) . '</td>';
        $html .= '</tr>';
    }

    $html .= '</tbody></table>';

    // Load the HTML content into Dompdf
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');

    // Render the PDF
    try {
        $dompdf->render();
        // Output the generated PDF
        $dompdf->stream("logistics_report.pdf", array("Attachment" => true));
    } catch (Exception $e) {
        // Handle error during PDF generation
        echo 'Error generating PDF: ' . $e->getMessage();
    }

    exit();
}

// Excel Export Function
function exportToExcel($logisticsData) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="FMS_Report.csv";');

    // Open output stream for CSV
    $output = fopen('php://output', 'w');

    // Add header row to CSV
    fputcsv($output, ['Sn#', 'Client Name', 'Pickup Location', 'Destination', 'Vehicle', 'Status', 'Pickup Date']);

    // Populate the CSV with logistics data
    foreach ($logisticsData as $index => $logistic) {
        fputcsv($output, [
            $index + 1,
            htmlspecialchars($logistic['client_name']),
            htmlspecialchars($logistic['pickup_location']),
            htmlspecialchars($logistic['destination']),
            htmlspecialchars($logistic['vehicle']),
            htmlspecialchars($logistic['status']),
            htmlspecialchars($logistic['pickup_date'])
        ]);
    }

    // Close output stream
    fclose($output);
    exit();
}

?>


<!-- Main Section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="mt-4">Generate Logistics Reports</h4>
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">Reports</li>
    </ol>

    <!-- Filter Form -->
    <form method="GET" action="reports.php">
        <div class="row mb-3">
            <div class="col-md-4">
                <label for="filter">Filter By:</label>
                <select name="filter" id="filter" class="form-control" onchange="toggleDateFields()">
                    <option value="week" <?php echo ($filter == 'week') ? 'selected' : ''; ?>>This Week</option>
                    <option value="month" <?php echo ($filter == 'month') ? 'selected' : ''; ?>>This Month</option>
                    <option value="range" <?php echo ($filter == 'range') ? 'selected' : ''; ?>>Custom Range</option>
                </select>
            </div>

            <div class="col-md-4" id="start_date_div" style="display: <?php echo ($filter == 'range') ? 'block' : 'none'; ?>;">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $_GET['start_date'] ?? ''; ?>">
            </div>
            <div class="col-md-4" id="end_date_div" style="display: <?php echo ($filter == 'range') ? 'block' : 'none'; ?>;">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $_GET['end_date'] ?? ''; ?>">
            </div>

            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary d-block">Generate Report</button>
            </div>
        </div>
    </form>

    <!-- Export Buttons -->
    <div class="d-flex justify-content-start mb-3">
        <a href="#" class="btn btn-secondary me-2" onclick="window.print();">Print Report</a>
        <a href="?filter=<?php echo $filter; ?>&export=pdf" class="btn btn-outline-danger me-2">Download PDF</a>
        <a href="?filter=<?php echo $filter; ?>&export=excel" class="btn btn-outline-success">Download Excel</a>
    </div>

    <!-- Display error message if any -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Display Reports Table -->
    <?php if (!empty($logisticsData)): ?>
        <table class="table table-striped table-hover mt-3">
            <thead>
                <tr>
                    <th>Sn#</th>
                    <th>Client Name</th>
                    <th>Pickup Location</th>
                    <th>Destination</th>
                    <th>Vehicle</th>
                    <th>Status</th>
                    <th>Pickup Date</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($logisticsData as $index => $logistic): ?>
                    <tr>
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($logistic['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($logistic['pickup_location']); ?></td>
                        <td><?php echo htmlspecialchars($logistic['destination']); ?></td>
                        <td><?php echo htmlspecialchars($logistic['vehicle']); ?></td>
                        <td><?php echo htmlspecialchars($logistic['status']); ?></td>
                        <td><?php echo htmlspecialchars($logistic['pickup_date']); ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p class="text-center text-danger">
            No records found for the selected filter.
        </p>
    <?php endif; ?>
</main>

<!-- JavaScript to toggle date fields -->
<script>
function toggleDateFields() {
    const filterSelect = document.getElementById('filter');
    const startDateDiv = document.getElementById('start_date_div');
    const endDateDiv = document.getElementById('end_date_div');
    if (filterSelect.value === 'range') {
        startDateDiv.style.display = 'block';
        endDateDiv.style.display = 'block';
    } else {
        startDateDiv.style.display = 'none';
        endDateDiv.style.display = 'none';
    }
}
</script>

<?php include './includes/footer.php'; ?>
