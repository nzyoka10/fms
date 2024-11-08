<?php
// get_client_data.php
header('Content-Type: application/json');
include 'includes/functions.php';

// Check if the request method is POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the raw POST data
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if JSON was successfully decoded
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON format']);
        exit;
    }

    // Ensure the client ID is provided and is not empty
    if (isset($data['id']) && !empty($data['id'])) {
        $clientId = $data['id'];

        // Check if database connection is established
        if (!$conn) {
            echo json_encode(['success' => false, 'message' => 'Database connection error. Please try again later.']);
            exit;
        }

        // Fetch client data
        $clientData = getClientData($conn, $clientId);

        if ($clientData) {
            // Respond with the client data
            echo json_encode([
                'success' => true,
                'client_name' => $clientData['client_name'],
                'deceased' => $clientData['deceased_name'],
                'service_type' => $clientData['service_type'],
                'schedule_date' => $clientData['schedule_date'],
                'vehicle_type' => $clientData['vehicle_type'],
                'request' => $clientData['request']
            ]);
        } else {
            // Client not found
            echo json_encode(['success' => false, 'message' => 'Client not found']);
        }
    } else {
        // Invalid or missing client ID
        echo json_encode(['success' => false, 'message' => 'Invalid client ID']);
    }

    // Close the database connection (recommended for cleanup)
    if ($conn) {
        $conn->close();
    }
} else {
    // Invalid request method
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
