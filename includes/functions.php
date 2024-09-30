<?php
// functions.php

// database configuration file
require_once 'config.php';

// Check if the function doesn't already exist to prevent redeclaration
if (!function_exists('userExists')) {

    /**
     * Check if a user exists by username or email.
     *
     * @param string $username The username to check.
     * @param string $email The email to check.
     * @return bool True if user exists, false otherwise.
     */
    function userExists($username, $email)
    {
        global $conn;

        // Prepare the SQL statement to check for existing username or email
        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $result = $stmt->get_result();

        // Return true if any record exists
        return $result->num_rows > 0;
    }
}

/**
 * Register a new user.
 *
 * @param string $firstName The first name of the new user.
 * @param string $lastName The last name of the new user.
 * @param string $username The username of the new user.
 * @param string $email The email of the new user.
 * @param string $password The password of the new user.
 * @param string $role The role of the new user (admin or staff).
 * @return bool True on success, false on failure.
 */
function registerUser($firstName, $lastName, $username, $email, $password, $role)
{
    global $conn;

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

    // Prepare the SQL statement to insert a new user
    $sql = "INSERT INTO users (first_name, last_name, username, email, password_hash, role, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($sql);

    // Bind parameters: s for string types (first_name, last_name, username, email, password_hash, role)
    $stmt->bind_param('ssssss', $firstName, $lastName, $username, $email, $hashedPassword, $role);

    // Execute the statement and return the result
    return $stmt->execute();
}

/**
 * Log in a user and start a session.
 *
 * @param string $username The username of the user.
 * @param string $password The password of the user.
 * @return mixed User ID on success, false on failure.
 */
function loginUser($username, $password)
{
    global $conn;

    // Prepare the SQL statement to retrieve user by username
    $sql = "SELECT * FROM users WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();

        // Verify the password
        if (password_verify($password, $user['password_hash'])) { // Use 'password_hash' field
            // Start a session and store user information
            session_start();
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role']; // Optional: Store user role if needed

            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit(); // Always exit after a header redirect
        }
    }

    // Return false if login fails
    return false;
}

/**
 * Fetch clients from the database.
 *
 * @return array
 */
function getClients()
{
    global $conn;

    $clients = [];
    $sql = "SELECT * FROM clients"; // Replace 'clients' with your actual clients table name
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // Fetch each row and store it in the $clients array
        while ($row = $result->fetch_assoc()) {
            $clients[] = $row;
        }
    }
    return $clients;
}

/**
 * Add a new client to the database.
 *
 * @param string $clientName The name of the client.
 * @param string|null $clientEmail The email of the client (optional).
 * @param string|null $clientPhone The phone number of the client (optional).
 * @param string|null $clientAddress The address of the client (optional).
 * @return bool True on success, false on failure.
 */
function addClient($clientName, $clientEmail = null, $clientPhone = null, $clientAddress = null)
{
    global $conn;

    // Prepare the SQL statement to insert a new client
    $sql = "INSERT INTO clients (full_name, email, phone, address, created_at, updated_at) VALUES (?, ?, ?, ?, NOW(), NOW())";
    $stmt = $conn->prepare($sql);

    // Bind parameters
    $stmt->bind_param('ssss', $clientName, $clientEmail, $clientPhone, $clientAddress);

    // Execute the statement and return the result
    return $stmt->execute();
}

/**
 * Get the total number of registered clients.
 *
 * @return int The total count of registered clients.
 */
function countRegisteredClients()
{
    global $conn;

    // Prepare the SQL query to count the clients
    $sql = "SELECT COUNT(*) as total FROM clients";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        return (int)$row['total']; // Return the total count as an integer
    }

    return 0;
}

// Fetch client details by ID
/**
 * Retrieves client details from the database using the client ID.
 *
 * @param int $id The ID of the client.
 * @return array The client's details as an associative array.
 */
function getClientById($id)
{
    global $conn;

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT * FROM clients WHERE id = ?");

    // Bind the ID parameter to the SQL statement
    $stmt->bind_param("i", $id);

    // Execute the prepared statement
    $stmt->execute();

    // Get the result set from the executed statement
    $result = $stmt->get_result();

    // Fetch and return a single client record as an associative array
    return $result->fetch_assoc();
}

// Update client details
/**
 * Updates the details of a client in the database.
 *
 * @param int $id The ID of the client to update.
 * @param string $name The new name of the client.
 * @param string $email The new email of the client.
 * @param string $phone The new phone number of the client.
 * @param string $address The new address of the client.
 * @throws Exception If the database update fails.
 */
function updateClient($id, $name, $email, $phone, $address)
{
    global $conn;

    // Prepare the SQL statement for updating client details
    $stmt = $conn->prepare("UPDATE clients SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");

    // Bind the parameters to the SQL statement
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $id);

    // Execute the prepared statement and check for errors
    if (!$stmt->execute()) {
        throw new Exception("Database Error: " . $stmt->error); // Throw an exception if the update fails
    }

    $stmt->close();
}

/**
 * Search for clients by name, email, or phone.
 *
 * @param string $query The search query.
 * @return array The list of matching clients.
 */
function searchClients($query)
{
    global $conn;
    $stmt = $conn->prepare("SELECT * FROM clients WHERE full_name LIKE ? OR email LIKE ? OR phone LIKE ?");
    $searchParam = '%' . $query . '%';
    $stmt->bind_param('sss', $searchParam, $searchParam, $searchParam);
    $stmt->execute();
    $result = $stmt->get_result();

    $clients = [];
    while ($row = $result->fetch_assoc()) {
        $clients[] = $row;
    }

    return $clients;
}

/** 
 * ********** Bookings Functions ****************
 */

/**
 * Function to handle the booking form submission.
 *
 * @param array $data The form data from the POST request.
 * @param mysqli $conn The database connection.
 * @return array An associative array containing success or error messages.
 */
function handleBookingForm($data, $conn)
{
    $response = [];

    // Get form data with htmlspecialchars to prevent XSS
    $client_id = htmlspecialchars($data['client_id'] ?? '');
    $service_type = htmlspecialchars($data['service_type'] ?? '');
    $schedule_date = htmlspecialchars($data['schedule_date'] ?? '');
    $vehicle_type = htmlspecialchars($data['vehicle_type'] ?? '');
    $request = htmlspecialchars($data['request'] ?? '');
    $status = htmlspecialchars($data['status'] ?? 'scheduled');

    // Validate required fields
    if (empty($client_id) || empty($service_type) || empty($schedule_date) || empty($vehicle_type) || empty($status)) {
        $response['error'] = 'Please fill in all required fields!';
    } else {
        // Check if the booking already exists
        if (bookingExists($client_id, $service_type, $schedule_date, $vehicle_type, $conn)) {
            $response['error'] = 'Booking already exists for this client and date!';
        } else {
            // Attempt to insert the booking
            $insertResponse = insertBooking($client_id, $service_type, $schedule_date, $vehicle_type, $request, $status, $conn);
            if ($insertResponse['success']) {
                $response['success'] = null;
            } else {
                $response['error'] = 'Error: Could not add booking. Please try again!';
            }
        }
    }

    return $response;
}



/**
 * Function to check if a booking already exists.
 *
 * @param string $client_id The ID of the client.
 * @param string $service_type The type of service (e.g., Burial/Cremation).
 * @param string $schedule_date The date of the booking.
 * @param string $vehicle_type The type of vehicle for the booking.
 * @param mysqli $conn The database connection.
 * @return bool True if the booking exists, false otherwise.
 */
function bookingExists($client_id, $service_type, $schedule_date, $vehicle_type, $conn)
{
    $sql = "SELECT COUNT(*) FROM schedules WHERE client_id = ? AND service_type = ? AND schedule_date = ? AND vehicle_type = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('ssss', $client_id, $service_type, $schedule_date, $vehicle_type);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    return $count > 0; // Return true if count is greater than 0
}

/**
 * Function to insert a new booking into the database.
 *
 * @param string $client_id The ID of the client.
 * @param string $service_type The type of service (e.g., Burial/Cremation).
 * @param string $schedule_date The date of the booking.
 * @param string $vehicle_type The type of vehicle for the booking.
 * @param string $request Any additional requests.
 * @param string $status The status of the booking.
 * @param mysqli $conn The database connection.
 * @return array An associative array indicating success or failure.
 */
function insertBooking($client_id, $service_type, $schedule_date, $vehicle_type, $request, $status, $conn)
{
    $response = ['success' => false];

    // Prepare the SQL statement
    $sql = "INSERT INTO schedules (client_id, service_type, schedule_date, vehicle_type, request, status)
            VALUES (?, ?, ?, ?, ?, ?)";

    // Prepare the SQL statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters
        $stmt->bind_param('ssssss', $client_id, $service_type, $schedule_date, $vehicle_type, $request, $status);

        // Execute the SQL statement
        if ($stmt->execute()) {
            $response['success'] = true;
        }

        // Close the statement
        $stmt->close();
    }

    return $response;
}


/**
 * Retrieves bookings from the schedules table.
 *
 * This function fetches all bookings along with the client's full name
 * from the database and returns them as an associative array.
 *
 * @return array $bookings An array of booking records.
 */
function getBookings()
{
    global $conn; // Use the global database connection variable

    // Prepare and execute the SQL query to fetch bookings
    $query = "SELECT s.id, c.full_name, s.service_type, s.schedule_date, s.status 
              FROM schedules s
              JOIN clients c ON s.client_id = c.id";

    // $query = "SELECT * FROM schedules";

    $result = $conn->query($query); // Execute the query

    // Check if the query executed successfully
    if (!$result) {
        echo "Error: " . $conn->error; // Output error message if the query fails
        return []; // Return an empty array if the query fails
    }

    // Fetch the bookings as an associative array
    $bookings = []; // Initialize an empty array for bookings
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row; // Append each row to the bookings array
    }

    return $bookings; // Return the list of bookings
}


/**
 * Get the total number of bookings made.
 *
 * @return int The total count of bookings.
 */
function countBookingsMade()
{
    global $conn; // Use the global database connection

    // Prepare the SQL query to count bookings made
    $sql = "SELECT COUNT(*) as total FROM schedules";
    $result = $conn->query($sql);

    // Check if the query execution was successful
    if ($result && $result->num_rows > 0) {
        // Fetch the row and return the total count as an integer
        $row = $result->fetch_assoc();
        return (int)$row['total']; // Return the total count as an integer
    }

    // Return 0 if the query fails or no bookings found
    return 0;
}

/**
 * Fetch a single booking by ID from the schedules table along with client details.
 *
 * @param int $bookingId The ID of the booking.
 * @return array|null The booking details as an associative array, or null if not found.
 */
function getBookingById($bookingId)
{
    global $conn; // Use the global database connection

    // Prepare the SQL query to fetch booking details from the schedules table
    $stmt = $conn->prepare("
        SELECT s.*, c.full_name AS client_name
        FROM schedules s
        JOIN clients c ON s.client_id = c.id
        WHERE s.id = ?
    ");
    $stmt->bind_param("i", $bookingId); // Bind the booking ID as a parameter

    // Execute the statement and check if the query was successful
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            return $result->fetch_assoc(); // Return the booking data as an associative array
        }
    }

    return null; // Return null if no booking is found
}

