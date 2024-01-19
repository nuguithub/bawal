<?php
session_start();

header("Content-Type: application/json");

include '../connectDB.php';

$method = $_SERVER['REQUEST_METHOD'];

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(['status' => 'error', 'message' => 'You need to login first!']);
    exit();
}

$event_id = isset($_GET['eventId']) ? $_GET['eventId'] : null;

switch ($method) {
    case 'GET':
        if ($event_id === null) {
            http_response_code(400); // Bad Request
            echo json_encode(['status' => 'error', 'message' => 'Event ID is missing']);
            exit();
        }

        $fetchEventQuery = "SELECT * FROM events WHERE id = ?";
        $stmtFetchEvent = $conn->prepare($fetchEventQuery);

        // Assuming $user_id is already defined
        if ($stmtFetchEvent && $event_id !== null) {
            $stmtFetchEvent->bind_param('i', $event_id);
            $stmtFetchEvent->execute();

            $result = $stmtFetchEvent->get_result();

            if ($result->num_rows > 0) {
                $eventDetails = $result->fetch_assoc();
                echo json_encode($eventDetails);
            } else {
                http_response_code(404); 
                echo json_encode(['error' => 'Event not found']);
            }

            $stmtFetchEvent->close();
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['error' => 'Internal Server Error']);
        }

        $conn->close();
        break;

    case 'POST':
        // Extract data from PUT request
        $title = $_POST['title'];
        $description = $_POST['description'];
        $newDate = $_POST['date'];
        $newTime = $_POST['time'];

        $newDateTime = "$newDate $newTime";

        // Update event
        $updateQuery = "UPDATE events 
                        SET title = ?, 
                            description = ?, 
                            date_time = ? 
                        WHERE id = ?";

        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("sssi", $title, $description, $newDateTime, $event_id);

        // Execute update query
        $updateResult = $stmt->execute();

        // Respond with success or error message
        if ($updateResult) {
            echo json_encode(['status' => 'success', 'message' => 'Event updated successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Error updating event']);
        }
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        break;
}

?>