<?php
// get_client_data.php
header('Content-Type: application/json');
include 'includes/functions.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decode the incoming JSON request
    $data = json_decode(file_get_contents('php://input'), true);

    // Check for JSON decoding errors
    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON format']);
        exit;
    }

    // Validate the client ID
    if (isset($data['id']) && !empty($data['id'])) {
        $clientId = intval($data['id']);

        // Main client query
        $query = "SELECT client_name, deceased_name, service_type, schedule_date, vehicle_type, request 
                  FROM clients 
                  WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("i", $clientId);

        if ($stmt->execute()) {
            $result = $stmt->get_result();

            if ($result->num_rows > 0) {
                $clientData = $result->fetch_assoc();

                // Fetch related data (e.g., inventory)
                $relatedQuery = "SELECT inventory_item, quantity, description 
                                 FROM orders 
                                 WHERE client_id = ?";
                $relatedStmt = $conn->prepare($relatedQuery);
                $relatedStmt->bind_param("i", $clientId);
                $relatedStmt->execute();
                $relatedResult = $relatedStmt->get_result();

                $relatedData = [];
                while ($row = $relatedResult->fetch_assoc()) {
                    $relatedData[] = $row; // Collect related data
                }

                echo json_encode([
                    'success' => true,
                    'client' => $clientData,
                    'related' => $relatedData
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Client not found']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Query execution failed']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing client ID']);
    }

    $conn->close(); // Close the database connection
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
