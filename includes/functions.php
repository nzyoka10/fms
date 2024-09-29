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
function searchClients($query) {
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
 * Retrieves bookings from the schedules table.
 *
 * This function fetches all bookings along with the client's full name
 * from the database and returns them as an associative array.
 *
 * @return array $bookings An array of booking records.
 */
function getBookings() {
    global $conn; // Use the global database connection variable

    // Prepare and execute the SQL query to fetch bookings
    // $query = "SELECT s.id, c.full_name, s.service_type, s.schedule_date, s.location, s.status 
    //           FROM schedules s
    //           JOIN clients c ON s.client_id = c.id"; 

    $query = "SELECT * FROM schedules";

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
 * Adds a new booking to the schedules table.
 *
 * This function inserts a new booking into the database with the specified details.
 *
 * @param string $clientName The name of the client.
 * @param string $serviceType The type of service for the booking.
 * @param string $bookingDate The date of the booking.
 * @param string $location The location of the booking.
 * @param string $locationLink The Google Maps link for the location.
 * @param string $status The status of the booking (e.g., scheduled, completed).
 *
 * @return bool True on success, False on failure.
 */
function addBooking($clientName, $serviceType, $bookingDate, $location, $locationLink, $status) {
    global $conn; // Use the global database connection variable

    // Prepare an SQL statement to prevent SQL injection
    $stmt = $conn->prepare("INSERT INTO schedules (client_id, service_type, schedule_date, location, google_map_link, status) VALUES (?, ?, ?, ?, ?, ?)");

    // Check if the statement preparation failed
    if ($stmt === false) {
        error_log("Error preparing statement: " . $conn->error); // Log error for debugging
        return false;
    }

    // Get the client ID based on the client name
    $clientId = getClientIdByName($clientName); // You need to implement this function

    // Check if the client ID was retrieved successfully
    if ($clientId === null) {
        error_log("Client not found: $clientName"); // Log error for debugging
        return false; // Return false if the client does not exist
    }

    // Bind parameters to the prepared statement
    if (!$stmt->bind_param("isssss", $clientId, $serviceType, $bookingDate, $location, $locationLink, $status)) {
        error_log("Error binding parameters: " . $stmt->error); // Log error for debugging
        $stmt->close(); // Always close the statement
        return false;
    }

    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Error executing statement: " . $stmt->error); // Log error for debugging
        $stmt->close(); // Close the statement to free up resources
        return false;
    }

    // Close the statement
    $stmt->close(); // Close the statement to free up resources

    return true; // Return true on successful insertion
}

/**
 * Retrieves the client ID based on the client's name.
 *
 * @param string $clientName The name of the client.
 *
 * @return int|null The client ID if found, or null if not found.
 */
function getClientIdByName($clientName) {
    global $conn; // Use the global database connection variable

    // Prepare the SQL statement to retrieve the client ID based on the client's name
    $stmt = $conn->prepare("SELECT id FROM clients WHERE full_name = ?");
    
    // Check if the statement preparation failed
    if ($stmt === false) {
        error_log("Error preparing getClientIdByName statement: " . $conn->error); // Log error for debugging
        return null;
    }

    // Bind the client's name to the prepared statement
    $stmt->bind_param("s", $clientName);

    // Execute the statement
    if (!$stmt->execute()) {
        error_log("Error executing getClientIdByName statement: " . $stmt->error); // Log error for debugging
        $stmt->close(); // Always close the statement
        return null;
    }

    // Get the result
    $result = $stmt->get_result();

    // Check if the client was found
    if ($result->num_rows > 0) {
        // Fetch the client ID
        $row = $result->fetch_assoc();
        $clientId = $row['id'];
    } else {
        // Client not found
        $clientId = null;
    }

    // Close the statement
    $stmt->close();

    return $clientId; // Return the client ID or null if not found
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
 * Fetch a single booking by ID from the schedules table.
 *
 * @param int $bookingId The ID of the booking.
 * @return array|null The booking details as an associative array, or null if not found.
 */
function getBookingById($bookingId)
{
    global $conn; // Use the global database connection

    // Prepare the SQL query to fetch booking details from the schedules table
    $stmt = $conn->prepare("SELECT * FROM schedules WHERE id = ?");
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


// /**
//  * Fetch a single booking by ID from the database.
//  *
//  * @param int $bookingId The ID of the booking.
//  * @return array|null The booking details as an associative array, or null if not found.
//  */
// function getBookingById($bookingId)
// {
//     global $conn; // Use the global database connection

//     // Prepare the SQL query
//     $stmt = $conn->prepare("SELECT schedules.*, clients.full_name as client_name FROM schedules 
//                             JOIN clients ON schedules.client_id = clients.id 
//                             WHERE schedules.id = ?");
//     $stmt->bind_param("i", $bookingId); // Bind the booking ID as a parameter

//     if ($stmt->execute()) {
//         $result = $stmt->get_result();
//         if ($result->num_rows > 0) {
//             return $result->fetch_assoc(); // Return the booking data as an associative array
//         }
//     }

//     return null; // Return null if no booking is found
// }

