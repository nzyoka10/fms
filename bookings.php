<?php

// Include functions file
include 'includes/functions.php';

// Handle form submission for adding a new booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientName = $_POST['client_name'] ?? null; // Get the client's ID from the select box
    $serviceType = $_POST['service_type'] ?? null; // Get the service type
    $bookingDate = $_POST['bookingdate'] ?? null; // Get the booking date
    $location = $_POST['location'] ?? null; // Get the location
    $locationLink = $_POST['locationlink'] ?? null; // Get the location link
    $status = $_POST['status'] ?? 'scheduled'; // Default status to 'scheduled'

    // Assuming addBooking is a function you created to handle the database insertion
    if (addBooking($clientName, $serviceType, $bookingDate, $location, $locationLink, $status)) {
        // Redirect to the same page after successful submission
        header("Location: bookings.php");
        exit();
    } else {
        // Log the error for debugging
        error_log("Error adding booking: Client Name: $clientName, Service Type: $serviceType, Booking Date: $bookingDate, Location: $location, Location Link: $locationLink, Status: $status");

        // Set error message for display
        $errorMessage = "Error adding booking. Please try again.";
    }
}

// Fetch all bookings
$bookings = getBookings();

// More includes of HTML templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">

    <h4 class="mt-4">Listing of bookings</h4>
    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard / Bookings</li>
    </ol>

    <!-- Header buttons -->
    <div class="d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-4 border-bottom-none">
        <!-- Add Client Button -->
        <!-- Button trigger modal -->
        <button type="button" class="btn btn-sm btn-primary me-5 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <span data-feather="user-plus"></span>&nbsp;Add booking
        </button>

    </div>

    <?php
    // Display error message if it exists
    if (isset($errorMessage)) {
        echo "<div class='alert alert-danger mt-2'>$errorMessage</div>";
    }
    ?>

    <!-- Bookings Table -->
    <table class="table table-striped" id="bookingsTable">
        <thead>
            <tr>
                <th>Sn#</th>
                <th>Client Name</th>
                <th>Service Type</th>
                <th>Booked Day</th>
                <th>status</th>
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
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($booking['client_id']); ?></td>
                        <td><?php echo htmlspecialchars($booking['service_type']); ?></td>
                        <td><?php echo htmlspecialchars($booking['schedule_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="viewBooking.php?id=<?php echo $booking['id']; ?>">View</a></li>
                                    <li><a class="dropdown-item" href="editBooking.php?id=<?php echo $booking['id']; ?>">Update</a></li>
                                    <!-- <li><a class="dropdown-item" href="deleteClientBooking.php?id=<?php echo $booking['id']; ?>">Delete</a></li> -->
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- bookings modal form -->
    <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="staticBackdropLabel">New Booking</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <!-- user form -->
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="client_name" class="form-label">Client Name<span class="text-danger">*</span></label>
                                <select class="form-select" id="client_name" name="client_name" required>
                                    <option value="" disabled selected>Select client</option>
                                    <?php
                                    // Fetch clients from the database and populate options
                                    $query = "SELECT id, full_name FROM clients";
                                    $result = $conn->query($query);
                                    if ($result) {
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='{$row['id']}'>{$row['full_name']}</option>";
                                        }
                                    }
                                    ?>
                                </select>
                                <!-- <input type="text" class="form-control" placeholder="First name" aria-label="First name"> -->
                            </div>
                            <div class="col-md-6 mb-3">
                                <legend class="col-form-label col-sm-10 pt-2">Service Type<span class="text-danger">*</span></legend>
                                <div class="col">
                                    <input class="form-check-input" type="radio" name="burial" id="burial" value="burial">
                                    <label class="form-check-label me-2" for="burial">
                                        Burial
                                    </label>
                                    <input class="form-check-input" type="radio" name="cremation" id="cremation" value="cremation">
                                    <label class="form-check-label me-2" for="cremation">
                                        Cremation
                                    </label>                                    
                                    <input class="form-check-input" type="radio" name="other" id="other" value="other">
                                    <label class="form-check-label me-2" for="other">
                                        Other
                                    </label>                                    
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="service_type" class="form-label">Vehicle Type<span class="text-danger">*</span></label>
                                <select class="form-select" id="service_type" name="service_type" required>
                                    <option value="" disabled selected>Select vehicle</option>
                                    <option value="van">Van - Body + 6 people</option>
                                    <option value="bus">Bus - Body + 30 people</option>
                                    <option value="other">Other</option>
                                </select>                                
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="bookingdate" class="form-label">Booking Date<span class="text-danger">*</span></label>
                                <input type="date" class="form-control custom-date" id="bookingdate" name="bookingdate" required>
                            </div>                            
                        </div>
                    </form>

                </div>
                <!-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Understood</button>
                </div> -->
            </div>
        </div>
    </div>

    <!-- Add Client Modal Form -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">New Booking</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="needs-validation" novalidate>
                        <!-- Client's name -->
                        <div class="mb-3">
                            <label for="client_name" class="form-label">Client Name</label>
                            <select class="form-select" id="client_name" name="client_name" required>
                                <option value="" disabled selected>Select client</option>
                                <?php
                                // Fetch clients from the database and populate options
                                $query = "SELECT id, full_name FROM clients";
                                $result = $conn->query($query);
                                if ($result) {
                                    while ($row = $result->fetch_assoc()) {
                                        echo "<option value='{$row['id']}'>{$row['full_name']}</option>";
                                    }
                                }
                                ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col mb-3">
                                <label for="service_type" class="form-label">Service Type</label>
                                <select class="form-select" id="service_type" name="service_type" required>
                                    <option value="" disabled selected>Select service</option>
                                    <option value="burial">Burial</option>
                                    <option value="cremation">Cremation</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col mb-3">
                                <label for="bookingdate" class="form-label">Booking Date</label>
                                <input type="date" class="form-control" id="bookingdate" name="bookingdate" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col mb-3">
                                <label for="location" class="form-label">Location</label>
                                <input type="text" class="form-control" id="location" name="location" required>
                            </div>
                            <div class="col mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="scheduled">Scheduled</option>
                                    <option value="completed">Completed</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="locationlink" class="form-label">Map Link</label>
                            <input type="text" class="form-control" id="locationlink" name="locationlink" required>
                        </div>

                        <button type="submit" class="btn btn-sm btn-primary px-3">Add Record</button>
                    </form>

                    <script>
                        $(document).ready(function() {
                            // Initialize Select2 for the client name select
                            $('#client_name').select2({
                                placeholder: "Select Client",
                                allowClear: true
                            });
                        });
                    </script>

                </div>
            </div>
        </div>
    </div>



</main>

<?php include './includes/footer.php'; ?>