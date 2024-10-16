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
    $sql = "SELECT * FROM clients";
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

        // Return the total count as an integer
        return (int)$row['total'];
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
    // Initialize an empty array for bookings
    $bookings = []; 
    while ($row = $result->fetch_assoc()) {

        // Append each row to the bookings array
        $bookings[] = $row; 
    }

    // Return the list of bookings
    return $bookings; 
}


/**
 * Get the total number of bookings made.
 *
 * @return int The total count of bookings.
 */
function countBookingsMade()
{
    global $conn;

    // Prepare the SQL query to count bookings made
    $sql = "SELECT COUNT(*) as total FROM schedules";
    $result = $conn->query($sql);

    // Check if the query execution was successful
    if ($result && $result->num_rows > 0) {
        // Fetch the row and return the total count as an integer
        $row = $result->fetch_assoc();

        // Return the total count as an integer
        return (int)$row['total']; 
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
    global $conn;

    // Prepare the SQL query to fetch booking details from the schedules table
    $stmt = $conn->prepare("
        SELECT s.*, c.full_name AS client_name
        FROM schedules s
        JOIN clients c ON s.client_id = c.id
        WHERE s.id = ?
    ");
    // Bind the booking ID as a parameter
    $stmt->bind_param("i", $bookingId);

    // Execute the statement and check if the query was successful
    if ($stmt->execute()) {
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {

            // Return the booking data as an associative array
            return $result->fetch_assoc();
        }
    }

    return null;
}

/**
 * Updates an existing booking record in the database.
 *
 * @param int $bookingId The ID of the booking to update.
 * @param int $client_id The ID of the client associated with the booking.
 * @param string $service_type The type of service for the booking (e.g., burial, cremation).
 * @param string $schedule_date The scheduled date for the booking.
 * @param string $vehicle_type The type of vehicle for transportation.
 * @param string $request Any special requests related to the booking.
 * @param string $status The current status of the booking (e.g., scheduled, completed).
 * @param mysqli $conn The database connection object.
 * @return array An array indicating success or failure of the update operation.
 */
function updateBooking($bookingId, $client_id, $service_type, $schedule_date, $vehicle_type, $request, $status, $conn)
{
    // Prepare the SQL query to update the booking details.
    $query = "UPDATE schedules SET client_id = ?, service_type = ?, schedule_date = ?, vehicle_type = ?, request = ?, status = ? WHERE id = ?";

    // Prepare the statement for execution.
    $stmt = $conn->prepare($query);

    // Bind the parameters to the prepared statement.
    $stmt->bind_param("isssssi", $client_id, $service_type, $schedule_date, $vehicle_type, $request, $status, $bookingId);

    // Execute the prepared statement to perform the update.
    $stmt->execute();

    // Return an array indicating whether the update was successful.
    return ['success' => $stmt->affected_rows > 0];
}

// Function to handle the booking update
function handleUpdateBookingForm($data, $conn, $bookingId)
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
        // Attempt to update the booking
        $updateResponse = updateBooking($bookingId, $client_id, $service_type, $schedule_date, $vehicle_type, $request, $status, $conn);
        if ($updateResponse['success']) {
            $response['success'] = 'Booking updated successfully!';
        } else {
            $response['error'] = 'Error: Could not update booking. Please try again!';
        }
    }

    return $response;
}

/** 
 * Payments function
 * 
 */

/** 
 * Function to handle the payment processing.
 *
 * This function processes the payment by retrieving the required details from the input,
 * validating them, and inserting a new payment record into the database. 
 * It generates an 8-digit receipt number and retrieves the corresponding booking ID 
 * from the schedules table.
 *
 * @param array $data  An associative array containing payment details.
 * @param mysqli $conn  The database connection object.
 *
 * @return array  An associative array containing the status of the payment processing.
 */
function handlePaymentForm($data, $conn, $bookingId)
{
    $response = []; // Initialize response array

    // Get form data and sanitize input
    $payment_method = htmlspecialchars($data['payment_method'] ?? ''); // Retrieve and sanitize payment method
    $amount = htmlspecialchars($data['amount'] ?? ''); // Retrieve and sanitize amount
    $tax = htmlspecialchars($data['tax'] ?? '0'); // Retrieve and sanitize tax, default to 0
    $discount = htmlspecialchars($data['discount'] ?? '0'); // Retrieve and sanitize discount, default to 0

    // Validate required fields
    if (empty($payment_method) || empty($amount)) {
        $response['error'] = 'Please fill in all required fields!'; // Set error message if required fields are empty
        return $response; // Return early with error
    }

    // Check if the booking exists using the provided booking ID
    $bookingDetails = getBookingById($bookingId);

    // Check if the booking exists
    if ($bookingDetails === null) {
        $response['error'] = 'Invalid booking ID.';
        return $response;
    }

    // Generate the next receipt number
    $query = "SELECT COALESCE(MAX(receipt_number), 137280) + 1 AS next_receipt FROM payments"; 
    $result = $conn->query($query); 
    $row = $result->fetch_assoc(); 
    $receipt_number = str_pad($row['next_receipt'], 8, '0', STR_PAD_LEFT);

    // Prepare SQL query to insert payment record
    $insertQuery = "INSERT INTO payments (booking_id, receipt_number, payment_method, amount, tax, discount, payment_date) VALUES (?, ?, ?, ?, ?, ?, CURDATE())";
    $stmt = $conn->prepare($insertQuery);
    $stmt->bind_param("issddd", $bookingId, $receipt_number, $payment_method, $amount, $tax, $discount);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        $response['success'] = 'Payment processed successfully! Your receipt number is: ' . $receipt_number;
    } else {
        $response['error'] = 'Error: Could not process payment. Please try again!';
    }
    $stmt->close(); 

    return $response;
}

// Function to get Total revenue
/**
 * Function to fetch total revenue from payments table.
 * 
 * This function calculates the total revenue by summing up all the payment amounts in the database.
 * 
 * @param mysqli $conn  The database connection object.
 * @return float  The total revenue. Returns 0 if no payments are found.
 */
function fetchTotalRevenue($conn)
{
    // Initialize total revenue as 0
    $totalRevenue = 0;

    // Query to sum up all payment amounts
    $query = "SELECT SUM(amount) AS total_revenue FROM payments";

    // Execute the query
    $result = $conn->query($query);

    // Check if the query was successful and fetch the result
    if ($result) {
        $row = $result->fetch_assoc();
        $totalRevenue = $row['total_revenue'] ?? 0; 
    }

    return (float) $totalRevenue;
}

/**
 * Get the total number of completed services.
 *
 * This function retrieves the total number of services marked as "completed"
 * in the bookings or services table.
 *
 * @param mysqli $conn The database connection object.
 * @return int The number of completed services.
 */
function getCompletedServicesCount($conn)
{
    // Query to count the number of completed services
    $query = "SELECT COUNT(*) AS completed_count FROM schedules WHERE status = 'completed'";

    // Execute the query
    $result = $conn->query($query);

    // Check if the query was successful and fetch the result
    if ($result && $row = $result->fetch_assoc()) {
        return (int)$row['completed_count'];
    } else {
        return 0;
    }
}

/**
 * Get the total number of pending tasks.
 *
 * This function retrieves the total number of tasks or services marked as "pending"
 * in the bookings or tasks table.
 *
 * @param mysqli $conn The database connection object.
 * @return int The number of pending tasks.
 */
function getPendingTasksCount($conn)
{
    // Query to count the number of pending tasks/services
    $query = "SELECT COUNT(*) AS pending_count FROM schedules WHERE status = 'scheduled'";

    // Execute the query
    $result = $conn->query($query);

    // Check if the query was successful and fetch the result
    if ($result && $row = $result->fetch_assoc()) {
        return (int)$row['pending_count'];
    } else {
        return 0;
    }
}

/**
 * Fetch all processed bookings from the payments table and client name from the clients table.
 *
 * @param mysqli $conn  The database connection object.
 * @return array  Returns an array of processed bookings.
 */
function getProcessedBookings($conn)
{
    $processedBookings = [];

    // SQL query to get booking data from the payments table and client name from clients table
    $query = "SELECT p.booking_id, p.receipt_number, p.payment_method, p.amount, p.tax, p.discount, p.payment_date, 
                     s.schedule_date, c.full_name AS client_name
              FROM payments p
              JOIN schedules s ON p.booking_id = s.id
              JOIN clients c ON s.client_id = c.id
              ORDER BY p.payment_date DESC";

    $result = $conn->query($query);

    // Check if the query returns rows
    if ($result && $result->num_rows > 0) {
        // Fetch each row as an associative array and store it in $processedBookings
        while ($row = $result->fetch_assoc()) {
            $processedBookings[] = $row;
        }
    }

    return $processedBookings; 
}

/**
 * Fetch all users from the users table.
 *
 * @param mysqli $conn  The database connection object.
 * @return array  Returns an array of users.
 */
function getUsers($conn)
{
    $users = [];

    // SQL query to get data from the users table
    $query = "SELECT id, username, email, role, created_at FROM users ORDER BY created_at Asc";

    $result = $conn->query($query);

    // Check if the query returns rows
    if ($result && $result->num_rows > 0) {
        // Fetch each row as an associative array and store in $users
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
    }

    return $users;
}

/**
 * Delete a user by their ID.
 *
 * @param mysqli $conn  The database connection object.
 * @param int $userId  The ID of the user to delete.
 * @return bool  True if the user was deleted, false otherwise.
 */
function deleteUserById($conn, $userId)
{
    // Prepare the SQL delete query
    $sql = "DELETE FROM users WHERE id = ?";

    // Prepare a statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the user ID as an integer parameter
        $stmt->bind_param("i", $userId);

        // Execute the statement
        if ($stmt->execute()) {
            // Return true if the deletion was successful
            return true;
        }
        // Close the statement
        $stmt->close();
    }

    // Return false if the deletion failed
    return false;
}

// Function to get clients with valid bookings
function getValidBookingsWithClients($conn) {
    // SQL query to fetch client information for clients who have valid bookings.
    // It selects the client ID and client name from the 'clients' table 
    // and joins it with the 'bookings' table on the 'client_id'.
    // The 'WHERE' clause ensures that only bookings with a status of 'confirmed' are selected.
    $query = "
        SELECT 
            clients.id AS client_id, 
            clients.full_name AS client_name
        FROM schedules
        JOIN clients ON schedules.client_id = clients.id
        WHERE schedules.status = 'scheduled'";  // Adjust the status condition as needed for "valid" bookings
    
    // Prepare the SQL query for execution to prevent SQL injection
    $stmt = $conn->prepare($query);
    
    // Execute the query to fetch results
    $stmt->execute();
    
    // Store the results of the query in a variable
    $result = $stmt->get_result();
    
    // Initialize an empty array to store valid clients
    $validClients = [];
    
    // Fetch each row from the result set and add it to the array
    while ($row = $result->fetch_assoc()) {
        $validClients[] = $row; // Append the client data to the $validClients array
    }

    // Return the array of valid clients (clients with confirmed bookings)
    return $validClients;
}

/**
 * Schedules a logistics entry in the database.
 * 
 * @param mysqli $conn - The database connection object.
 * @param int $client_id - The ID of the client.
 * @param string $vehicle - The vehicle assigned for the logistics.
 * @param string $driver_name - The name of the driver assigned for the logistics.
 * @param string $pickup_date - The scheduled date for the logistics pickup.
 * @param string $destination - The final destination for the logistics.
 * @param string $pickup_location - The location where the client will be picked up.
 * @param string $status - The status of the logistics entry, defaults to 'pending'.
 * @return bool - Returns true if the logistics entry is successfully inserted, otherwise false.
 */
function scheduleLogistic($conn, $client_id, $vehicle, $driver_name, $pickup_date, $destination, $pickup_location, $status = 'pending') {
    // SQL query to insert a new logistics record into the 'logistics' table
    $query = "
        INSERT INTO logistics 
        (client_id, vehicle, driver_name, pickup_date, destination, pickup_location, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ";

    // Prepare the SQL statement using the database connection
    $stmt = $conn->prepare($query);

    // Bind the variables to the SQL statement as parameters:
    // 'i' refers to an integer (client_id), and 's' refers to strings (vehicle, driver_name, pickup_date, destination, pickup_location, status)
    $stmt->bind_param('issssss', $client_id, $vehicle, $driver_name, $pickup_date, $destination, $pickup_location, $status);

    // Execute the statement and check for success
    if ($stmt->execute()) {
        // Return true if the logistics entry was inserted successfully into the database
        return true;
    } else {
        // Return false if there was an error executing the query
        return false;
    }
}


/**
 * Update a logistics entry in the database.
 *
 * @param mysqli $conn The database connection object.
 * @param int $id The ID of the logistics entry.
 * @param int $client_id The ID of the client.
 * @param string $vehicle The vehicle assigned for the logistics.
 * @param string $driver_name The name of the driver assigned for the logistics.
 * @param string $pickup_date The scheduled date for the logistics pickup.
 * @param string $destination The final destination for the logistics.
 * @param string $pickup_location The location where the client will be picked up.
 * @param string $status The status of the logistics.
 * @return bool Returns true if the update was successful, otherwise false.
 */
function updateLogistic($conn, $id, $client_id, $vehicle, $driver_name, $pickup_date, $destination, $pickup_location, $status) {
    $query = "UPDATE logistics SET client_id = ?, vehicle = ?, driver_name = ?, pickup_date = ?, destination = ?, pickup_location = ?, status = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('issssssi', $client_id, $vehicle, $driver_name, $pickup_date, $destination, $pickup_location, $status, $id);
    
    return $stmt->execute();
}

/**
 * Fetch logistics data from the database and return as an array.
 *
 * @param mysqli $conn The database connection object.
 * @return array Returns an array of logistics data, including client name.
 */
function getLogisticsData($conn) {
    $logisticsData = [];

    // SQL query to get logistics data and client name, including pickup_location
    $query = "SELECT l.id, l.client_id, l.vehicle, l.driver_name, l.pickup_date, l.destination, l.status, 
                     l.pickup_location, c.full_name AS client_name, l.created_at, l.updated_at
              FROM logistics l
              JOIN clients c ON l.client_id = c.id
              ORDER BY l.created_at DESC";

    // Execute the query
    $result = $conn->query($query);

    // Check if the query returns rows
    if ($result && $result->num_rows > 0) {
        // Fetch each row as an associative array and store in $logisticsData
        while ($row = $result->fetch_assoc()) {
            $logisticsData[] = $row;
        }
    }

    // Return the logistics data
    return $logisticsData;
}

/**
 * Fetch the logistics record by ID from the database.
 *
 * @param mysqli $conn The database connection object.
 * @param int $logistic_id The ID of the logistic record.
 * @return array|bool Returns the logistic record as an associative array, or false if not found.
 */
function getLogisticById($conn, $logistic_id) {
    // SQL query to get logistics data by ID
    $query = "SELECT l.id, l.vehicle, l.driver_name, l.pickup_date, l.destination, l.status, 
                     c.full_name AS client_name, l.pickup_location, l.created_at, l.updated_at
              FROM logistics l
              JOIN clients c ON l.client_id = c.id
              WHERE l.id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);
    $stmt->bind_param('i', $logistic_id);
    $stmt->execute();

    // Get the result and fetch the data
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return false;
    }
}

/**
 * Update the logistics record in the database.
 *
 * @param mysqli $conn The database connection object.
 * @param int $logistic_id The ID of the logistic record.
 * @param string $vehicle The updated vehicle name.
 * @param string $driver_name The updated driver name.
 * @param string $pickup_date The updated pickup date.
 * @param string $destination The updated destination.
 * @param string $status The updated status.
 * @return bool Returns true if the update is successful, otherwise false.
 */
function updateLogisticRecord($conn, $logistic_id, $vehicle, $driver_name, $pickup_location, $pickup_date, $destination, $status) {
    $query = "UPDATE logistics 
              SET vehicle = ?, driver_name = ?, pickup_location = ?, pickup_date = ?, destination = ?, status = ?, updated_at = NOW() 
              WHERE id = ?";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssssi', $vehicle, $driver_name, $pickup_location, $pickup_date, $destination, $status, $logistic_id);
    return $stmt->execute();
}

/**
 * Fetch logistics data for the current week.
 * 
 * @param mysqli $conn The database connection object.
 * @return array Returns an array of logistics data.
 */
function getLogisticsDataByWeek($conn) {
    $logisticsData = [];
    $query = "SELECT l.id, l.vehicle, l.driver_name, l.pickup_location, l.pickup_date, l.destination, l.status, 
                     c.full_name AS client_name
              FROM logistics l
              JOIN clients c ON l.client_id = c.id
              WHERE WEEK(l.pickup_date) = WEEK(CURDATE())
              ORDER BY l.pickup_date DESC";

    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $logisticsData[] = $row;
        }
    }

    return $logisticsData;
}

/**
 * Fetch logistics data for the current month.
 * 
 * @param mysqli $conn The database connection object.
 * @return array Returns an array of logistics data.
 */
function getLogisticsDataByMonth($conn) {
    $logisticsData = [];
    $query = "SELECT l.id, l.vehicle, l.driver_name, l.pickup_location, l.pickup_date, l.destination, l.status, 
                     c.full_name AS client_name
              FROM logistics l
              JOIN clients c ON l.client_id = c.id
              WHERE MONTH(l.pickup_date) = MONTH(CURDATE())
              ORDER BY l.pickup_date DESC";

    $result = $conn->query($query);
    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $logisticsData[] = $row;
        }
    }

    return $logisticsData;
}

/**
 * Fetch logistics data based on a custom date range.
 * 
 * @param mysqli $conn The database connection object.
 * @param string $startDate The start date of the range.
 * @param string $endDate The end date of the range.
 * @return array Returns an array of logistics data.
 */
function getLogisticsDataByRange($conn, $startDate, $endDate) {
    $logisticsData = [];
    $query = "SELECT l.id, l.vehicle, l.driver_name, l.pickup_location, l.destination, l.status, 
                     c.full_name AS client_name
              FROM logistics l
              JOIN clients c ON l.client_id = c.id
              WHERE l.pickup_date BETWEEN ? AND ?
              ORDER BY l.pickup_date DESC";

    $stmt = $conn->prepare($query);
    $stmt->bind_param('ss', $startDate, $endDate);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $logisticsData[] = $row;
        }
    }

    return $logisticsData;
}




