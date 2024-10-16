<?php

// Include functions file
include 'includes/functions.php';

// Handle form submission for adding a client
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $clientName = $_POST['client_name'];
    $clientEmail = $_POST['client_email'] ?? null;
    $clientPhone = $_POST['client_phone'] ?? null;
    $clientAddress = $_POST['client_address'] ?? null;

    // Add the client to the database
    if (addClient($clientName, $clientEmail, $clientPhone, $clientAddress)) {
        // Redirect to the same page after successful submission
        header("Location: clients.php");
        exit();
    } else {
        // Optionally handle an error case
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

    <h4 class="mt-3">Listing of clients</h4>

    <ol class="breadcrumb mb-2">
        <li class="breadcrumb-item active">Dashboard</li>
        <li class="breadcrumb-item">
            <a class="text-decoration-none hover-underline" href="./clients.php">Clients</a>
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
    <table class="table table-striped" id="clientsTable">
        <thead>
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
                        <td><?php echo $index + 1; ?></td>
                        <td><?php echo htmlspecialchars($client['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($client['full_name']); ?></td>
                        <td><?php echo htmlspecialchars($client['email']); ?></td>
                        <td>
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="viewClient.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-success btn-sm">View</a></li>
                                    <li><a class="dropdown-item" href="editClient.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-warning btn-sm">Update</a></li>
                                    <!-- <li><a class="dropdown-item" href="delete_client.php?id=<?php echo $client['id']; ?>" class="btn btn-sm btn-danger btn-sm">Delete</a></li> -->
                                </ul>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Add Client Modal Form -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                        Add Client
                    </h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" class="needs-validation">
                        <div class="mb-3">
                            <label for="client_name" class="form-label">Client Name</label>
                            <input type="text" class="form-control" id="client_name" name="client_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="client_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="client_email" name="client_email">
                        </div>
                        <div class="mb-3">
                            <label for="client_phone" class="form-label">Phone Number</label>
                            <input type="text" class="form-control" id="client_phone" name="client_phone">
                        </div>
                        <div class="mb-3">
                            <label for="client_address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="client_address" name="client_address">
                        </div>
                        <button type="submit" class="btn btn-sm btn-primary px-3">Save Record</button>
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
            const clientName = row.cells[2].textContent.toLowerCase();
            const clientEmail = row.cells[3].textContent.toLowerCase();

            // Show the row if it matches the search query
            if (clientName.includes(query) || clientEmail.includes(query)) {
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