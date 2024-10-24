<?php

// Include functions file
include 'includes/functions.php';

// Fetch the client ID from the URL parameter
$id = $_GET['id'];
$client = getClientById($id); // Retrieve client details

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $clientId = $_POST['client_id'];
    $clientName = $_POST['client_name'];
    $clientEmail = $_POST['client_email'];
    $clientPhone = $_POST['client_phone'];
    $clientAddress = $_POST['client_address'];

    $deceasedName = $_POST['deceased_name'];
    $deceasedAge = $_POST['deceased_age'];
    $deceasedDateOfDeath = $_POST['deceased_date_of_death'];
    $deceasedCause = $_POST['deceased_cause'];
    $deceasedGender = $_POST['deceased_gender'];

    // Call the updateClient function
    try {
        updateClient($clientId, $clientName, $clientEmail, $clientPhone, $clientAddress, $deceasedName, $deceasedAge, $deceasedDateOfDeath, $deceasedCause, $deceasedGender);
        // echo "Client and deceased person information updated successfully!";

        // Redirect to the dashboard on successful update
        header("Location: clients.php");
        exit();
    } catch (Exception $e) {
        echo "Failed to update the record: " . $e->getMessage();
    }
}

// more includes of html templates
include './includes/header.php';
include './includes/sidebar.php';
?>

<!-- Main section -->
<main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-2">

    <div class="container-fluid px-4">
        <!-- Page heading -->
        <h4 class="text-muted text-capitalize mt-2">Client Record</h4>

        <ol class="breadcrumb mb-2">
            <li class="breadcrumb-item active">Clients</li>
            <li class="breadcrumb-item mb-0">
                <a class="text-decoration-none hover-underline" href="viewClient.php?id=<?php echo $client['id']; ?>">Update</a>
            </li>
        </ol>

        <div class="card mb-2">

            <div class="card-header d-flex align-items-center">
                <span data-feather="info" class="me-2"></span>Update client's information
            </div>
            <div class="card-body">

                <div class="card-body">
                    <form method="POST" action="<?php echo $_SERVER["PHP_SELF"] . '?id=' . $id; ?>" class="login-form">

                        <div class="row">
                            <!-- Client's Name Field -->
                            <div class="col-md-4 mb-3">
                                <label for="name" class="sr-only">Full Name</label>
                                <input type="text" class="form-control rounded-left" id="name" name="name" aria-label="Full Name" placeholder="Full Name" value="<?php echo htmlspecialchars($client['client_name']); ?>" required>
                            </div>

                            <!-- Client's Email Field -->
                            <div class="col-md-4 mb-3">
                                <label for="email" class="sr-only">Email</label>
                                <input type="email" class="form-control rounded-left" id="email" name="email" aria-label="Email" placeholder="Email" value="<?php echo htmlspecialchars($client['client_email']); ?>" required>
                            </div>

                            <!-- Client's Phone Field -->
                            <div class="col-md-4 mb-3">
                                <label for="phone" class="sr-only">Phone</label>
                                <input type="text" class="form-control rounded-left" id="phone" name="phone" aria-label="Phone" placeholder="Phone" value="<?php echo htmlspecialchars($client['client_phone']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Client's Address Field -->
                            <div class="col-md-4 mb-3">
                                <label for="address" class="sr-only">Address</label>
                                <input type="text" class="form-control rounded-left" id="address" name="client_address" aria-label="Address" placeholder="Address" value="<?php echo htmlspecialchars($client['client_address']); ?>" required>
                            </div>

                            <!-- Deceased Person's Name Field -->
                            <div class="col-md-4 mb-3">
                                <label for="deceased_name" class="sr-only">Deceased Name</label>
                                <input type="text" class="form-control rounded-left" id="deceased_name" name="deceased_name" aria-label="Deceased Name" placeholder="Deceased Full Name" value="<?php echo htmlspecialchars($client['deceased_name']); ?>" required>
                            </div>

                            <!-- Deceased Person's Age Field -->
                            <div class="col-md-4 mb-3">
                                <label for="deceased_age" class="sr-only">Deceased Age</label>
                                <input type="number" class="form-control rounded-left" id="deceased_age" name="deceased_age" aria-label="Deceased Age" placeholder="Deceased Age" value="<?php echo htmlspecialchars($client['deceased_age']); ?>" required>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Deceased Person's Date of Death Field -->
                            <div class="col-md-4 mb-3">
                                <label for="deceased_date_of_death" class="sr-only">Date of Death</label>
                                <input type="date" class="form-control rounded-left" id="deceased_date_of_death" name="deceased_date_of_death" aria-label="Date of Death" value="<?php echo htmlspecialchars($client['deceased_date_of_death']); ?>" required>
                            </div>

                            <!-- Deceased Person's Cause of Death Field -->
                            <div class="col-md-4 mb-3">
                                <label for="deceased_cause" class="sr-only">Cause of Death</label>
                                <select class="form-control rounded-left" id="deceased_cause" name="deceased_cause" required>
                                    <option value="" disabled>Select Cause</option>
                                    <option value="natural" <?php echo $client['deceased_cause'] == 'natural' ? 'selected' : ''; ?>>Natural</option>
                                    <option value="sickness" <?php echo $client['deceased_cause'] == 'sickness' ? 'selected' : ''; ?>>Sickness</option>
                                    <option value="accident" <?php echo $client['deceased_cause'] == 'accident' ? 'selected' : ''; ?>>Accident</option>
                                    <option value="other" <?php echo $client['deceased_cause'] == 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>

                            <!-- Deceased Person's Gender Field -->
                            <div class="col-md-4 mb-3">
                                <label for="deceased_gender" class="sr-only">Gender</label>
                                <select class="form-control rounded-left" id="deceased_gender" name="deceased_gender" required>
                                    <option value="" disabled>Select Gender</option>
                                    <option value="male" <?php echo $client['deceased_gender'] == 'male' ? 'selected' : ''; ?>>Male</option>
                                    <option value="female" <?php echo $client['deceased_gender'] == 'female' ? 'selected' : ''; ?>>Female</option>
                                    <option value="other" <?php echo $client['deceased_gender'] == 'other' ? 'selected' : ''; ?>>Other</option>
                                </select>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-group">
                            <input type="submit" class="btn btn-sm btn-success" value="Update Client">
                        </div>

                    </form>
                </div>
            </div>


        </div>


        <?php include './includes/footer.php'; ?>