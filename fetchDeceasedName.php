<?php

// Include functions file
require_once "./includes/config.php";

header('Content-Type: application/json');

// Check if client_id is provided
if (isset($_GET['client_id']) && is_numeric($_GET['client_id'])) {
    $client_id = intval($_GET['client_id']);

    // Query to fetch deceased name for the selected client ID
    $stmt = $conn->prepare("SELECT deceased_name FROM clients WHERE id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $row = $result->fetch_assoc()) {
        echo json_encode(['success' => true, 'deceased_name' => $row['deceased_name']]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Deceased name not found']);
    }

    $stmt->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid client ID']);
}

// $conn->close();
?>
