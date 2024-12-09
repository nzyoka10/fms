<?php

// Include necessary files
include 'includes/functions.php';
include './includes/header.php';
include './includes/sidebar.php';
require './vendor/autoload.php'; // Autoload for Dompdf

// Import the Dompdf class
use Dompdf\Dompdf;

// Initialize variables
$logisticsData = []; // Array to store logistics data
$error = ''; // Error message
$filter = ''; // Selected filter

// Fetch company info for report header
$query = "SELECT * FROM company_info ORDER BY created_at DESC LIMIT 1";
$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    $companyData = mysqli_fetch_assoc($result);

    // Store company data in global variables
    $GLOBALS['companyName'] = $companyData['company_name'];
    $GLOBALS['companyAddress'] = $companyData['company_address'];
    $GLOBALS['companyContact'] = $companyData['company_contact'];
    $GLOBALS['companyEmail'] = $companyData['company_email'];
    $GLOBALS['reportType'] = $companyData['report_type'];
} else {
    echo "No company data found.";
    exit; // Exit if no company data is found
}

// Handle filter form submission
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    // Fetch logistics data based on the selected filter
    if ($filter == 'week') {
        $logisticsData = getLogisticsDataByWeek($conn);
    } elseif ($filter == 'month') {
        $logisticsData = getLogisticsDataByMonth($conn);
    } elseif ($filter == 'range') {
        $startDate = $_GET['start_date'] ?? '';
        $endDate = $_GET['end_date'] ?? '';

        if (!empty($startDate) && !empty($endDate)) {
            $logisticsData = getLogisticsDataByRange($conn, $startDate, $endDate);
        } else {
            $error = 'Please select a valid date range.';
        }
    } else {
        $error = 'Invalid filter option selected.';
    }
}

// Handle export requests
if (isset($_GET['export'])) {
    if ($_GET['export'] == 'pdf') {
        exportToPDF($logisticsData);
    } elseif ($_GET['export'] == 'excel') {
        exportToExcel($logisticsData);
    }
}

// Function to export logistics data to PDF
function exportToPDF($logisticsData) {
    $dompdf = new Dompdf();

    // Check if company data exists
    if (!isset($GLOBALS['companyName'])) {
        echo "Company information is not set.";
        return;
    }

    // Add report header in PDF content
    $html = '<div style="text-align: center; margin-bottom: 20px;">';
    $html .= '<h2>' . htmlspecialchars($GLOBALS['companyName']) . '</h2>';
    $html .= '<p>' . htmlspecialchars($GLOBALS['companyAddress']) . '</p>';
    $html .= '<p>Contact: ' . htmlspecialchars($GLOBALS['companyContact']) . '</p>';
    $html .= '<p>Email: ' . htmlspecialchars($GLOBALS['companyEmail']) . '</p>';
    $html .= '<h4>' . htmlspecialchars($GLOBALS['reportType']) . '</h4>';
    $html .= '<hr></div>';

    // Add table content
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width: 100%;">';
    $html .= '<thead><tr><th>Sn#</th><th>Client Name</th><th>Pickup Location</th><th>Destination</th><th>Vehicle</th><th>Status</th><th>Pickup Date</th></tr></thead>';
    $html .= '<tbody>';

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

    // Generate PDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');
    $dompdf->render();
    $dompdf->stream("logistics_report.pdf", array("Attachment" => true));
}

// Function to export logistics data to Excel
function exportToExcel($logisticsData) {
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="logistics_report.csv";');

    $output = fopen('php://output', 'w');
    fputcsv($output, ['Sn#', 'Client Name', 'Pickup Location', 'Destination', 'Vehicle', 'Status', 'Pickup Date']);

    foreach ($logisticsData as $index => $logistic) {
        fputcsv($output, [
            $index + 1,
            htmlspecialchars($logistic['client_name']),
            htmlspecialchars($logistic['pickup_location']),
            htmlspecialchars($logistic['destination']),
            htmlspecialchars($logistic['vehicle']),
            htmlspecialchars($logistic['status']),
            htmlspecialchars($logistic['pickup_date']),
        ]);
    }

    fclose($output);
    exit();
}
?>


<style>
    @media print {
        .print-header {
            display: block; /* Make it visible in print */
        }
    }

    @media screen {
        .print-header {
            display: none; /* Hide it on screen */
        }
    }
</style>






<!-- Main Section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="text-muted mt-2">Generate Reports</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./reports.php">Reports</a>
        </li>
    </ol>

    <!-- Filter Form -->
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <div class="row mb-3">
            <div class="col-md-4 mb-2">
                <label for="filter">Filter By:</label>
                <select name="filter" id="filter" class="form-select sm-select" onchange="toggleDateFields()">
                    <option value="#!" selected>Filter report by:</option>
                    <option value="week" <?php echo ($filter == 'week') ? 'selected' : ''; ?>>This Week</option>
                    <option value="month" <?php echo ($filter == 'month') ? 'selected' : ''; ?>>This Month</option>
                    <option value="range" <?php echo ($filter == 'range') ? 'selected' : ''; ?>>Custom Range</option>
                </select>
            </div>

            <div class="col-md-2" id="start_date_div" style="display: <?php echo ($filter == 'range') ? 'block' : 'none'; ?>;">
                <label for="start_date">Start Date:</label>
                <input type="date" name="start_date" class="form-control" value="<?php echo $_GET['start_date'] ?? ''; ?>">
            </div>
            <div class="col-md-2" id="end_date_div" style="display: <?php echo ($filter == 'range') ? 'block' : 'none'; ?>;">
                <label for="end_date">End Date:</label>
                <input type="date" name="end_date" class="form-control" value="<?php echo $_GET['end_date'] ?? ''; ?>">
            </div>

            <div class="col-md-2">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-sm btn-dark d-block">Filter</button>
            </div>
        </div>
    </form>

    <!-- Export Buttons -->
    <div class="d-flex justify-content-start mb-3">
        <button type="button" class="btn btn-sm btn-success me-2" onclick="printReport();">Print</button>
        <a href="?export=pdf" class="btn btn-sm btn-danger me-2">Export to PDF</a>
        <a href="?export=excel" class="btn btn-sm btn-primary">Export to Excel</a>
    </div>

    <!-- Display Error -->
    <?php if ($error) { echo "<div class='alert alert-danger'>$error</div>"; } ?>

    <!-- Logistics Data Table -->
     <!-- Logistics Data Table -->
<div class="table-responsive">

<table class="table table-striped table-bordered table-hover table-lg">
    <thead class="table-dark">
        <tr>
            <th>Sn#</th>
            <th>Deceased Person</th>
            <th>Pickup</th>
            <th>Vehicle</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php if (empty($logisticsData)) { ?>
            <tr>
                <td colspan="6" class="text-center">No data available for the selected filter.</td>
            </tr>
        <?php } else { ?>
            <?php foreach ($logisticsData as $index => $logistic) { ?>
                <tr>
                    <td><?php echo ($index + 1); ?></td>
                    <td><?php echo htmlspecialchars($logistic['deceased_name']); ?></td>
                    <td><?php echo htmlspecialchars($logistic['schedule_date']); ?></td>
                    <td class="text-capitalize"><?php echo htmlspecialchars($logistic['vehicle_type']); ?></td>
                    <td><?php echo htmlspecialchars($logistic['status']); ?></td>
                    <td>
                        <a href="viewClientReport.php?id=<?php echo htmlspecialchars($logistic['id']); ?>" class="btn btn-sm btn-link">
                            View
                        </a>
                        <!-- <a href="#!" class="btn btn-sm btn-link">
                            Print
                        </a> -->
                    </td>
                </tr>
            <?php } ?>
        <?php } ?>
    </tbody>
</table>


</div>





</main>







<script>
    // Toggle date fields based on filter selection
    function toggleDateFields() {
        const filter = document.getElementById('filter').value;
        document.getElementById('start_date_div').style.display = filter === 'range' ? 'block' : 'none';
        document.getElementById('end_date_div').style.display = filter === 'range' ? 'block' : 'none';
    }

    // Print the report
    function printReport() {
        window.print();
    }

    // JavaScript to handle dynamic modal data loading
document.addEventListener('DOMContentLoaded', function() {
    // Add event listener to each "View" button
    const viewButtons = document.querySelectorAll('[data-bs-toggle="modal"]');
    viewButtons.forEach(button => {
        button.addEventListener('click', function() {
            const clientId = this.getAttribute('data-bs-id'); // Get the client ID
            loadClientData(clientId); // Load the data into the modal
        });
    });
});



</script>

<!-- Footer -->
<?php include './includes/footer.php'; ?>
