<?php

// Include necessary files
include 'includes/functions.php';
include './includes/header.php';
include './includes/sidebar.php';
require './vendor/autoload.php'; // Fixed missing semicolon

// Import the Dompdf class
use Dompdf\Dompdf;

// Initialize variables
$logisticsData = []; // Ensure it's an array even if no data is returned
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
    fputcsv($output, ['Sn#', 'Client Name', 'Pickup Location', 'Destination', 'Vehicle', 'Status', 'Pickup Date']);

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
                <th>Client Name</th>
                <th>Pickup</th>
                <th>Deceased Person</th>
                <th>Vehicle</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($logisticsData)) { ?>
                <tr>
                    <td colspan="7" class="text-center">No data available for the selected filter.</td>
                </tr>
            <?php } else { ?>
                <?php foreach ($logisticsData as $index => $logistic) { ?>
                    <tr>
                        <td><?php echo ($index + 1); ?></td>
                        <td><?php echo htmlspecialchars($logistic['client_name']); ?></td>
                        <td><?php echo htmlspecialchars($logistic['schedule_date']); ?></td>
                        <td><?php echo htmlspecialchars($logistic['deceased_name']); ?></td>
                        <td><?php echo htmlspecialchars($logistic['vehicle_type']); ?></td>
                        <td><?php echo htmlspecialchars($logistic['status']); ?></td>
                        <td>
                            <!-- View button to trigger modal -->
                            <button type="button" class="btn btn-sm btn-outline-dark" 
                                data-bs-toggle="modal" 
                                data-bs-target="#staticBackdrop" 
                                data-bs-id="<?php echo $booking['id']; ?>">
                                    View
                        </button>

                        </td>
                    </tr>
                <?php } ?>
            <?php } ?>
        </tbody>
    </table>
</div>


<!-- Modal to display individual report and have print button -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="staticBackdropLabel">Client Details</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body" id="clientDetailsContent">
        <!-- Dynamic content will be loaded here -->
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-sm btn-dark" onclick="printClientDetails()">Print</button>
        <button type="button" class="btn btn-sm btn-danger" data-bs-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>



</main>

<script>
    // Toggle date fields based on filter selection
    function toggleDateFields() {
        var filterValue = document.getElementById('filter').value;
        document.getElementById('start_date_div').style.display = filterValue === 'range' ? 'block' : 'none';
        document.getElementById('end_date_div').style.display = filterValue === 'range' ? 'block' : 'none';
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

// Function to load client data via AJAX
function loadClientData(clientId) {
    const modalBody = document.getElementById('clientDetailsContent');
    const loadingText = '<p>Loading client data...</p>';
    
    // Show loading message
    modalBody.innerHTML = loadingText;

    fetch('get_client_data.php', { 
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ id: clientId }) // Sending client ID as `id`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('Network response was not ok');
        }
        return response.json();
    })
    .then(data => {
        // Check if data is received and successful
        if (data.success) {
            // Generate HTML content for the modal
            const clientDataHtml = `
                <table class="table table-bordered">
                    <tr><th>Client Name</th><td>${data.client_name}</td></tr>
                    <tr><th>Deceased</th><td>${data.deceased_name}</td></tr>
                    <tr><th>Service</th><td>${data.service_type}</td></tr>
                    <tr><th>Vehicle</th><td>${data.vehicle_type}</td></tr>
                    <tr><th>Request</th><td>${data.request}</td></tr>
                    <tr><th>Booked date</th><td>${data.schedule_date}</td></tr>
                </table>
            `;
            modalBody.innerHTML = clientDataHtml; // Insert client data into modal body
        } else {
            modalBody.innerHTML = `<p class="text-danger">${data.message}</p>`; // Show error message if no data
        }
    })
    .catch(error => {
        console.error('Error loading client data:', error);
        modalBody.innerHTML = '<p class="text-danger">An error occurred while fetching client data. Please try again later.</p>';
    });
}

// Function to print client details
function printClientDetails() {
    const modalBody = document.getElementById('clientDetailsContent');
    const printContent = modalBody.innerHTML;
    
    // Open a new print window and print the content
    const printWindow = window.open('', '', 'width=1200,height=800');
    printWindow.document.write(`
        <html>
            <head>
                <title>Print Client Details</title>
                <style>
                    body { font-family: Arial, sans-serif; padding: 20px; }
                    h2 { text-align: center; }
                    .table-bordered { width: 100%; border-collapse: collapse; }
                    .table-bordered th, .table-bordered td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    .table-bordered th { background-color: #f2f2f2; font-weight: bold; }
                </style>
            </head>
            <body>
                <h2>Client FMS Report</h2>
                ${printContent}
            </body>
        </html>
    `);
    printWindow.document.close();
    printWindow.print();
}



</script>

<!-- Footer -->
<?php include './includes/footer.php'; ?>
