<?php

// Include necessary files
include 'includes/functions.php';
include './includes/header.php';
include './includes/sidebar.php';
require './vendor/autoload.php'; // Fixed missing semicolon

// Import the Dompdf class
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

// Handle Export Requests
if (isset($_GET['export'])) {
    if ($_GET['export'] == 'pdf') {
        exportToPDF($logisticsData);
    } elseif ($_GET['export'] == 'excel') {
        exportToExcel($logisticsData);
    }
}

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
    exit; // Exit if no data found to avoid undefined variable errors later
}

// PDF Export Function
function exportToPDF($logisticsData) {
    $dompdf = new Dompdf();

    // Check if company data exists
    if (!isset($GLOBALS['companyName'])) {
        echo "Company information is not set.";
        return; // Exit if company data is not available
    }

    // Add the report header in the PDF content
    $html = '<div style="text-align: center; margin-bottom: 20px;">';
    $html .= '<h2>' . htmlspecialchars($GLOBALS['companyName']) . '</h2>';
    $html .= '<p>' . htmlspecialchars($GLOBALS['companyAddress']) . '</p>';
    $html .= '<p>Contact: ' . htmlspecialchars($GLOBALS['companyContact']) . '</p>';
    $html .= '<p>Email: ' . htmlspecialchars($GLOBALS['companyEmail']) . '</p>';
    $html .= '<h4>' . htmlspecialchars($GLOBALS['reportType']) . '</h4>';
    $html .= '<hr></div>';

    // Add the table content
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

    // Load HTML to DOMPDF
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'landscape');

    try {
        $dompdf->render();
        $dompdf->stream("logistics_report.pdf", array("Attachment" => true));
    } catch (Exception $e) {
        echo "Error generating PDF: " . $e->getMessage();
    }
}

// Excel Export Function
function exportToExcel($logisticsData)
{
    // Ensure there is no output before this point
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="FMS_Report.csv";');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['Sn#', 'Client Name', 'Pickup Location', 'Destination', 'Vehicle']);

    foreach ($logisticsData as $index => $logistic) {
        fputcsv($output, [
            $index + 1,
            htmlspecialchars($logistic['client_name']),
            htmlspecialchars($logistic['pickup_location']),
            htmlspecialchars($logistic['destination']),
            htmlspecialchars($logistic['vehicle']),
            // Uncomment if needed
            // htmlspecialchars($logistic['status']),
            // htmlspecialchars($logistic['pickup_date'])
        ]);
    }

    fclose($output);
    exit(); // Exit to ensure no further output is sent
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
    <form method="GET" action="reports.php">
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
        <a href="?filter=<?php echo $filter; ?>&export=pdf" class="btn btn-sm btn-outline-dark me-2">PDF</a>
        <a href="?filter=<?php echo $filter; ?>&export=excel" class="btn btn-sm btn-outline-secondary">Excel</a>
    </div>

    <!-- Display error message if any -->
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php endif; ?>

    <!-- Display Reports Table -->
    <div id="printSection">
        <!-- Hidden on screen but visible in print -->
        <div class="print-header" style="text-align: center; margin-bottom: 20px;">
            <h3 class="text-muted"><?php echo $companyName; ?></h3>
            <p class="text-muted"><?php echo $companyAddress; ?>&nbsp;|&nbsp;<?php echo $companyContact; ?></p>
            <!-- <p class="text-muted">Contact: </p> -->
            <p class="text-muted">Email: <?php echo $companyEmail; ?></p>
            <!-- <h4><?php echo $reportType; ?></h4> -->
            <hr>
        </div>


        <!-- Report Table -->
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
                            <td class="text-muted"><?php echo $index + 1; ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($logistic['client_name']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($logistic['pickup_location']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($logistic['destination']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($logistic['vehicle']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($logistic['status']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($logistic['pickup_date']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p class="alert alert-warning">No data found for the selected filter.</p>
        <?php endif; ?>
    </div>

</main>

<script>
    // Toggle Date Fields Based on Filter Selection
    function toggleDateFields() {
        const filter = document.getElementById('filter').value;
        const startDateDiv = document.getElementById('start_date_div');
        const endDateDiv = document.getElementById('end_date_div');

        if (filter === 'range') {
            startDateDiv.style.display = 'block';
            endDateDiv.style.display = 'block';
        } else {
            startDateDiv.style.display = 'none';
            endDateDiv.style.display = 'none';
        }
    }

    // Print the report
    function printReport() {
        const printContents = document.getElementById('printSection').innerHTML;
        const originalContents = document.body.innerHTML;

        // Set the body to only include the print section
        document.body.innerHTML = printContents;

        // Open the print dialog
        window.print();

        // Restore the original content
        document.body.innerHTML = originalContents;
    }
</script>

<?php include './includes/footer.php'; ?>