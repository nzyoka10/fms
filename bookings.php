<?php

// Include database connection and function files
require_once "./includes/functions.php";

// Initialize response message
$responseMessage = [];

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure that client_id, service_id, etc. are provided in the form submission
    if (!isset($_POST['client_id']) || !isset($_POST['service_type']) || !isset($_POST['schedule_date'])) {
        $responseMessage['error'] = "Missing required fields.";
    } else {
        // Call the function to handle the booking form submission
        $responseMessage = handleBookingForm($_POST, $conn);
    }
}

// Fetch all bookings from the database
$bookings = getBookings();

// Include HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">
    <h4 class="text-muted text-uppercase mt-3">Bookings</h4>

    <!-- Breadcrumbs -->
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Bookings</li>
        <li class="breadcrumb-item"><a href="./bookings.php" class="text-decoration-none">View</a></li>
    </ol>

   <!-- New Booking Button -->
<div class="d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-2 border-bottom-none">
    <button type="button" class="btn btn-sm btn-outline-dark me-5" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
        <span data-feather="user-plus"></span>&nbsp;New Booking
    </button>
</div>

<!-- Booking Modal Form -->
<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="staticBackdropLabel">New Booking</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body mt-4">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="row g-3">
                    <div class="row">
                        <!-- Client Name Field -->
                        <div class="col-md-4 mb-2">
                            <label for="client_id" class="form-label">Client Name</label>
                            <select class="form-control" id="client_id" name="client_id" required>
                                <option value="">Select Client</option>
                                <?php
                                // Fetch and display client options from the database
                                $clients = getClients($conn); // Fetch clients from database
                                foreach ($clients as $client) {
                                    echo "<option value='" . htmlspecialchars($client['id']) . "'>" . htmlspecialchars($client['client_name']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <!-- Deceased Name Field -->
                        <div class="col-md-4 mb-2">
                            <label for="deceased_name" class="form-label">Deceased Name</label>
                            <input type="text" class="form-control" id="deceased_name" name="deceased_name" required>
                        </div>

                        <!-- Service Type Field -->
                        <div class="col-md-4 mb-2">
                            <label for="service_type" class="form-label">Service Type</label>
                            <select class="form-select" id="service_type" name="service_type" required>
                                <option value="burial">Burial</option>
                                <option value="cremation">Cremation</option>
                                <option value="other">Other</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Schedule Date Field -->
                        <div class="col-md-4 mb-2">
                            <label for="schedule_date" class="form-label">Schedule Date</label>
                            <input type="date" class="form-control" id="schedule_date" name="schedule_date" required>
                        </div>

                        <!-- Vehicle Type Field -->
                        <div class="col-md-4 mb-2">
                            <label for="vehicle_type" class="form-label">Vehicle Type</label>
                            <input type="text" class="form-control" id="vehicle_type" name="vehicle_type">
                        </div>

                        <!-- Status Field -->
                        <div class="col-md-4 mb-2">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="scheduled">Scheduled</option>
                                <option value="completed">Completed</option>
                            </select>
                        </div>
                    </div>

                    <!-- Request Field -->
                    <div class="col-md-12 mb-2">
                        <label for="request" class="form-label">Any other request</label>
                        <textarea class="form-control" id="request" name="request" rows="3"></textarea>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-sm btn-success">Make Booking</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


    <!-- Display success or error messages -->
    <?php if (!empty($responseMessage)): ?>
        <div class="alert alert-<?php echo isset($responseMessage['success']) ? 'success' : 'danger'; ?> mt-2">
            <?php echo isset($responseMessage['success']) ? $responseMessage['success'] : $responseMessage['error']; ?>
        </div>
    <?php endif; ?>

    <!-- Bookings Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover table-lg">
            <thead class="table-dark">
                <tr>
                    <th>Sn#</th>
                    <th>Booked Date</th>
                    <th>Client Name</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($bookings)): ?>
                    <tr>
                        <td colspan="6" class="text-center text-danger">No bookings found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($bookings as $index => $booking): ?>
                        <tr>
                            <td><?php echo $index + 1; ?></td>
                            <td><?php echo htmlspecialchars($booking['schedule_date']); ?></td>
                            <td><?php echo htmlspecialchars($booking['client_name']); ?></td>
                            <td><?php echo htmlspecialchars($booking['service_type']); ?></td>
                            <td><?php echo htmlspecialchars($booking['status']); ?></td>
                            <td><a href="viewBooking.php?id=<?php echo $booking['id']; ?>" class="btn btn-sm btn-outline-secondary">View</a></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>


</main>

<!-- JavaScript to dynamically fetch deceased name based on client selection -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clientSelect = document.getElementById('client_id');
        const deceasedNameInput = document.getElementById('deceased_name');

        clientSelect.addEventListener('change', function() {
            const clientId = this.value;

            // AJAX request to fetch deceased name
            fetch(`fetchDeceasedName.php?client_id=${clientId}`)
                .then(response => response.json())
                .then(data => deceasedNameInput.value = data.success ? data.deceased_name : '')
                .catch(error => console.error('Error:', error));
        });
    });
</script>

<?php include './includes/footer.php'; ?>
