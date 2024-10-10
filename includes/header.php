<?php
// Start the session
session_start();

// Include functions file
require_once 'includes/functions.php';

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    // Redirect to the login page if not logged in
    header("Location: index.php");
    exit();
}

// Fetch total registered clients
$totalClients = countRegisteredClients();

// Fetch total bookings made
$totalBookings = countBookingsMade();

// Fetch the total revenue
$totalRevenue = fetchTotalRevenue($conn);

// Fetch completed services count
$completedServicesCount = getCompletedServicesCount($conn);

// Fetch pending tasks count
$pendingTasksCount = getPendingTasksCount($conn);

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="FMS Dashboard">
    <title>FMS - Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    <link rel="stylesheet" href="assets/css/dashboard.css">

    <!-- FullCalendar CSS -->
    <!-- <link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet" /> -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/6.1.15/index.min.js" rel="stylesheet" />

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;400;600&display=swap" rel="stylesheet">

    <style>
        .active {
            background-color: transparent !important;
        }

        .text-decoration-none {
            font-weight: 400;
            letter-spacing: 0.1rem;
            text-decoration: none !important;
        }

        .hover-underline:hover {
            color: #666;
            text-decoration: underline !important;
            text-underline-offset: 6px;
        }
    </style>
</head>

<body>

    <!-- Header section -->
    <header class="navbar navbar-dark sticky-top bg-dark p-2 flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="./dashboard.php">FMS</a>
        <button class="navbar-toggler position-absolute d-md-none collapsed mt-2" type="button" data-bs-toggle="collapse"
            data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- User dropdown menu -->
        <div class="dropdown ms-auto pe-5">
            <button class="btn btn-sm btn-outline-light rounded-60 dropdown-toggle d-flex align-items-center text-capitalize" type="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <span data-feather="user" class="me-2"></span>
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                <li><a class="dropdown-item" href="./profile.php">Profile</a></li>
                <li><a class="dropdown-item" href="./logout.php">Logout</a></li>
            </ul>
        </div>
    </header>

   