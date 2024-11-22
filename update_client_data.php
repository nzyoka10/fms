<?php
// update_client_data.php
header('Content-Type: application/json');
include 'includes/functions.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Decode incoming JSON data
    $data = json_decode(file_get_contents('php://input'), true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        echo json_encode(['success' => false, 'message' => 'Invalid JSON format']);
        exit;
    }

    // Validate input data
    if (
        isset($data['id'], $data['client_name'], $data['deceased_name'], $data['service_type'], $data['schedule_date']) &&
        !empty($data['id']) && !empty($data['client_name'])
    ) {
        $clientId = intval($data['id']);
        $clientName = $data['client_name'];
        $deceasedName = $data['deceased_name'];
        $serviceType = $data['service_type'];
        $scheduleDate = $data['schedule_date'];

        // Update client data
        $query = "UPDATE clients SET client_name = ?, deceased_name = ?, service_type = ?, schedule_date = ? WHERE id = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ssssi", $clientName, $deceasedName, $serviceType, $scheduleDate, $clientId);

        if ($stmt->execute()) {
            // Update related inventory/orders (optional example)
            if (isset($data['related']) && is_array($data['related'])) {
                foreach ($data['related'] as $related) {
                    if (isset($related['id'], $related['quantity'])) {
                        $relatedId = intval($related['id']);
                        $quantity = intval($related['quantity']);

                        $updateRelatedQuery = "UPDATE orders SET quantity = ? WHERE id = ? AND client_id = ?";
                        $updateStmt = $conn->prepare($updateRelatedQuery);
                        $updateStmt->bind_param("iii", $quantity, $relatedId, $clientId);
                        $updateStmt->execute();
                    }
                }
            }

            echo json_encode(['success' => true, 'message' => 'Client data updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to update client data']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid or missing data']);
    }

    $conn->close(); // Close the database connection
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
?>
