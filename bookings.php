<?php

// Include functions file
include 'includes/functions.php';

// Initialize response messages
$responseMessage = [];

// Handle form submission for adding a new booking
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Call the function to handle the booking form submission
    $responseMessage = handleBookingForm($_POST, $conn);
}

// Fetch all bookings from the database
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
        <button type="button" class="btn btn-sm btn-primary me-5 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
            <span data-feather="user-plus"></span>&nbsp;Add booking
        </button>
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
                <th>Booked date</th>
                <th>Client name</th>
                <th>Service type</th>
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
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($booking['schedule_date']); ?></td>
                        <td><?php echo htmlspecialchars($booking['full_name']); ?></td>
                        <td class="text-capitalize"><?php echo htmlspecialchars($booking['service_type']); ?></td>
                        <td><?php echo htmlspecialchars($booking['status']); ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="viewBooking.php?id=<?php echo $booking['id']; ?>">View</a></li>
                                    <li><a class="dropdown-item" href="editBooking.php?id=<?php echo $booking['id']; ?>">Update</a></li>
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
                    <h1 class="modal-title fs-5 text-center" id="staticBackdropLabel">New Booking</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- user form -->
                    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="client_id" class="form-label">Client Name<span class="text-danger">*</span></label>
                                <select class="form-select" id="client_id" name="client_id" required>
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
                            <div class="col-md-6 mb-3">
                                <legend class="col-form-label col-sm-10 pt-2">Service Type<span class="text-danger">*</span></legend>
                                <div class="col">
                                    <input class="form-check-input" type="radio" name="service_type" id="burial" value="Burial" required>
                                    <label class="form-check-label me-2" for="burial">Burial</label>

                                    <input class="form-check-input" type="radio" name="service_type" id="cremation" value="Cremation" required>
                                    <label class="form-check-label me-2" for="cremation">Cremation</label>

                                    <input class="form-check-input" type="radio" name="service_type" id="other" value="Other" required>
                                    <label class="form-check-label me-2" for="other">Other</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="schedule_date" class="form-label">Booking Date<span class="text-danger">*</span></label>
                                <input type="date" class="form-control custom-date" id="schedule_date" name="schedule_date" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="vehicle_type" class="form-label">Vehicle Type<span class="text-danger">*</span></label>
                                <select class="form-select" id="vehicle_type" name="vehicle_type" required>
                                    <option value="" disabled selected>Select vehicle</option>
                                    <option value="van">Van - Body + 6 people</option>
                                    <option value="bus">Bus - Body + 30 people</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label for="request" class="form-label">Any other request<span class="text-danger">*</span></label>
                                <textarea class="form-control" placeholder="Leave a comment here" id="request" name="request" style="height: 180px"></textarea>
                            </div>

                            <div class="col-md-4 d-flex flex-column justify-content-between align-items-stretch">
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status<span class="text-danger">*</span></label>
                                    <select class="form-select" id="status" name="status" required>
                                        <option value="scheduled">Scheduled</option>
                                        <option value="completed">Completed</option>
                                    </select>
                                </div>

                                <div class="mt-2">
                                    <button type="reset" class="btn btn-sm btn-dark w-100 mb-3">Reset form</button>
                                    <button type="submit" class="btn btn-sm btn-primary w-100">Add booking</button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>


</main>

<!-- Footer -->
<?php include './includes/footer.php'; ?>