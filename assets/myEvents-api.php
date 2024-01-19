<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

include '../connectDB.php';

switch ($method) {
    // display events
    case 'GET':
        $user_id = $_SESSION['user_id'];

        $result = $conn->query("SELECT 
            events.*,
            users.username AS event_manager,
            IFNULL(COUNT(participants.user_id), NULL) AS participants_count
        FROM events
        LEFT JOIN participants ON events.id = participants.event_id
        INNER JOIN users ON events.event_manager = users.id
        WHERE events.event_manager = $user_id
        GROUP BY events.id
        ORDER BY events.date_time ASC;");

        if (!$result) {
            die("SQL Error: " . $conn->error);
        }

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        echo json_encode($events);
        break;
        
    case 'DELETE': // Adding this case for cancellation
        parse_str(file_get_contents("php://input"), $_DELETE);
    
        $user_id = $_SESSION['user_id'];
        $event_id = $_DELETE['event_id']; // Assuming the request sends the event_id in the body
    
        $deleteQuery = "DELETE FROM events WHERE event_manager = $user_id AND id = $event_id";
    
        if ($conn->query($deleteQuery)) {
            http_response_code(200); // OK
             echo json_encode(['status' => 'success', 'message' => 'Attendance canceled successfully!']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Error canceling attendance']);
        }
    
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

$conn->close();
?>