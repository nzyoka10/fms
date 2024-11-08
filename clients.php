<?php

// Include functions file
include 'includes/functions.php';


// Handle form submission for adding a client
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientName = $_POST['client_name'];
    $clientPhone = $_POST['client_phone'];
    $clientEmail = $_POST['client_email'];
    $clientAddress = $_POST['client_address'];

    $deceasedName = $_POST['deceased_name'];
    $deceasedAge = $_POST['deceased_age'];
    $deceasedDateOfDeath = $_POST['deceased_date_of_death'];
    $deceasedCause = $_POST['deceased_cause'];
    $deceasedGender = $_POST['deceased_gender'];

    // Call the addClient function
    if (addClient($clientName, $clientEmail, $clientPhone, $clientAddress, $deceasedName, $deceasedAge, $deceasedDateOfDeath, $deceasedCause, $deceasedGender)) {
        // echo "Client and deceased person information added successfully!";
        header("Location: clients.php");
        exit();
    } else {
        // echo "Failed to add the record!";
        echo "Error adding client. Please try again.";
    }
}

// Fetch all clients
$clients = getClients();

// More includes of html templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">

    <h4 class="text-muted text-capitalize mt-2">all clients</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Clients</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./clients.php">View</a>
        </li>
    </ol>

    <!-- Header buttons -->
    <div class="d-flex justify-content-start flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-2 border-bottom-none">
        <!-- Add Client Button -->
        <button type="button" class="btn btn-sm btn-outline-dark me-5 d-flex align-items-center" data-bs-toggle="modal" data-bs-target="#exampleModal">
            <span data-feather="user-plus"></span>&nbsp;New Client
        </button>

        <!-- Search Bar -->
        <form class="d-flex" onsubmit="return false;">
            <input class="form-control me-2" type="search" id="searchInput" placeholder="Search client..." aria-label="Search" required>
            <button class="btn btn-sm btn-outline-success d-flex align-items-center" id="searchButton" type="button">
                <span data-feather="search"></span>&nbsp;Search
            </button>
        </form>
    </div>

    <!-- Clients Table -->
    <div class="table-responsive">
        <table class="table table-striped table-hover table-lg">
            <thead class="table-dark">
                <tr>
                    <th>Sn#</th>
                    <th>Date Created</th>
                    <th>Client Name</th>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($clients)): ?>
                    <tr>
                        <td colspan="5" class="text-center text-danger">
                            <strong>Ooops! No clients found.</strong>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($clients as $index => $client): ?>
                        <tr>
                            <td class="text-muted"><?php echo $index + 1; ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($client['created_at']); ?></td>
                            <td class="text-muted text-uppercase"><?php echo htmlspecialchars($client['client_name']); ?></td>
                            <td class="text-muted"><?php echo htmlspecialchars($client['client_email']); ?></td>
                            <td>
                                <a class="btn btn-sm btn-outline-secondary" href="viewClient.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-success btn-sm">View</a>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Add Client Modal Form -->
    <div class="modal modal-lg fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        New Record
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="needs-validation">
                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="client_name" class="form-label">Client Name</label>
                                <input type="text" class="form-control" id="client_name" name="client_name" placeholder="Full Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="client_phone" class="form-label">Phone Number</label>
                                <input type="text" class="form-control" id="client_phone" name="client_phone" placeholder="Phone Number" required>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-6 mb-3">
                                <label for="client_email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="client_email" name="client_email" placeholder="Email Address" required>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label for="client_address" class="form-label">Address</label>
                                <input type="text" class="form-control" id="client_address" name="client_address" placeholder="Address, City, Town" required>
                            </div>
                        </div>

                        <div class="row">
                            <h6 class="text-muted mt-3 mb-3">Deceased Persons</h6>

                            <div class="col-md-6 mb-3">
                                <label for="deceased_name" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="deceased_name" name="deceased_name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="deceased_age" class="form-label">Age</label>
                                <input type="number" class="form-control" id="deceased_age" name="deceased_age" required>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-4">
                                <label for="deceased_date_of_death" class="form-label">Date of Death</label>
                                <input type="date" class="form-control" id="deceased_date_of_death" name="deceased_date_of_death" required>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="deceased_cause" class="form-label">Cause</label>
                                <select class="form-select" id="deceased_cause" name="deceased_cause" required>
                                    <option value="">Select Cause</option>
                                    <option value="natural">Natural</option>
                                    <option value="sickness">Sickness</option>
                                    <option value="accident">Accident</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="deceased_gender" class="form-label">Gender</label>
                                <select class="form-select" id="deceased_gender" name="deceased_gender" required>
                                    <option value="">Select Gender</option>
                                    <option value="male">Male</option>
                                    <option value="female">Female</option>
                                    <option value="other">Other</option>
                                </select>
                            </div>
                        </div>


                        <button type="submit" class="btn btn-sm btn-outline-dark px-3">Add Record</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</main>

<?php include './includes/footer.php'; ?>

<!-- JavaScript for Filtering -->
<script>
    document.getElementById('searchInput').addEventListener('input', function() {
        const query = this.value.toLowerCase();
        const rows = document.querySelectorAll('#clientsTable tbody tr');
        let hasResults = false;

        rows.forEach(row => {
            const clientName = row.cells[2].textContent.toLowerCase(); // Assuming this is full name
            const clientEmail = row.cells[3].textContent.toLowerCase(); // Assuming this is email
            const clientPhone = row.cells[4].textContent.toLowerCase(); // Assuming this is phone number
            const deceasedName = row.cells[5].textContent.toLowerCase(); // Assuming this is deceased name

            // Show the row if it matches the search query
            if (clientName.includes(query) || clientEmail.includes(query) || clientPhone.includes(query) || deceasedName.includes(query)) {
                row.style.display = '';
                hasResults = true; // Set hasResults to true if there are matches
            } else {
                row.style.display = 'none';
            }
        });

        // Show message if no results found
        const messageRow = document.createElement('tr');
        const messageCell = document.createElement('td');
        messageCell.setAttribute('colspan', '5');
        messageCell.classList.add('text-center');
        messageCell.textContent = "No matching records found.";
        messageRow.appendChild(messageCell);

        // If no rows are visible and not empty
        if (!hasResults && Array.from(rows).some(row => row.style.display !== 'none')) {
            document.querySelector('#clientsTable tbody').appendChild(messageRow);
        } else {
            // Remove the message if results are found
            const existingMessageRow = document.querySelector('#clientsTable tbody tr:last-child');
            if (existingMessageRow && existingMessageRow.textContent.includes("No matching records found.")) {
                existingMessageRow.remove();
            }
        }
    });
</script>
