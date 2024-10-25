<?php

// Include functions file
require_once "./includes/functions.php";

// Initialize response messages
$responseMessage = [];

// Handle form submission for adding a new booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Call the function to handle the booking form submission
    $responseMessage = handleBookingForm($_POST, $conn);
}

// Fetch all bookings from the database
$bookings = getBookings();

$deceased_name = ''; // Initialize variable

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get the selected client ID
    $client_id = htmlspecialchars($_POST['client_id'] ?? '');

    // Fetch the deceased name for the selected client
    if (!empty($client_id)) {
        $deceased_name = getDeceasedNameByClientId($client_id, $conn); // Fetch the deceased name based on client ID
    }

}


// More includes of HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">

    <h4 class="text-muted text-uppercase mt-3">Bookings</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./bookings.php">Bookings</a>
        </li>
    </ol>

    <!-- Header buttons -->
    <div class="d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-2 border-bottom-none">
        <!-- Add Client Button -->
        <button type="button" class="btn btn-sm btn-outline-dark me-5 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <span data-feather="user-plus"></span>&nbsp;New booking
        </button>
    </div>


    <!-- Bookings Modal Form -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5 text-center" id="staticBackdropLabel">New Booking</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body mt-4">
                    <form action="" method="post" class="row g-3">
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
                                <input type="text" class="form-control" id="deceased_name" name="deceased_name" disabled>
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




    <?php
    // Display success or error messages if they exist
    if (!empty($responseMessage)) {
        if (isset($responseMessage['success'])) {
            echo "<div class='alert alert-success mt-2'>{$responseMessage['success']}</div>";
        }
        if (isset($responseMessage['error'])) {
            echo "<div class='alert alert-danger mt-2'>{$responseMessage['error']}</div>";
        }
    }
    ?>

    <!-- Bookings Table -->
    <table class="table table-striped" id="bookingsTable">
        <thead>
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
                    <td colspan="6" class="text-center text-danger">
                        <strong>Ooop! No bookings found.</strong>
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($bookings as $index => $booking): ?>
                    <tr>
                        <td class="text-muted"><?php echo $index + 1; ?></td>
                        <td class="text-muted"><?php echo htmlspecialchars($booking['schedule_date']); ?></td>
                        <td class="text-muted text-uppercase"><?php echo htmlspecialchars($booking['client_name']); ?></td> <!-- Updated field name -->
                        <td class="text-muted text-uppercase"><?php echo htmlspecialchars($booking['service_type']); ?></td>
                        <td class="text-muted text-uppercase"><?php echo htmlspecialchars($booking['status']); ?></td>
                        <td>
                            <a class="btn btn-sm btn-outline-secondary" href="viewBooking.php?id=<?php echo $booking['id']; ?>">View</a>
                            <!-- <a class="btn btn-sm btn-outline-warning" href="editBooking.php?id=<?php echo $booking['id']; ?>">Update</a> -->
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>


</main>


<script>
    document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.getElementById('client_id');
    const deceasedNameInput = document.getElementById('deceased_name');

    clientSelect.addEventListener('change', function() {
        const clientId = this.value;

        // Make AJAX request to fetch deceased name
        fetch(`fetchDeceasedName.php?client_id=${clientId}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    deceasedNameInput.value = data.deceased_name; // Set deceased name
                } else {
                    deceasedNameInput.value = ''; // Clear if no result
                }
            })
            .catch(error => console.error('Error fetching deceased name:', error));
    });
});

</script>


<!-- Footer -->
<?php include './includes/footer.php'; ?>