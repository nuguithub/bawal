<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

include '../connectDB.php';

switch ($method) {

    case 'GET':
        $user_id = $_SESSION['user_id'];

        $result = $conn->query("SELECT 
            events.id,
            events.title, 
            events.description, 
            events.date_time, 
            CONCAT(profile.firstName, ' ', profile.lastName) AS event_manager,
            COUNT(participants.user_id) AS participants_count
        FROM events
        LEFT JOIN participants ON events.id = participants.event_id
        INNER JOIN profile ON events.event_manager = profile.account_id
        WHERE participants.user_id = $user_id
        GROUP BY events.id
        ORDER BY events.date_time DESC;
        ;");

        if (!$result) {
            die("SQL Error: " . $conn->error);
        }

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        echo json_encode($events);
        break;
        
        
    case 'POST':
        if (!isset($_SESSION['loggedIn'])) {
            http_response_code(401); // Unauthorized
            echo json_encode(['status' => 'error', 'message' => 'You need to login first!', 'redirect' => 'profile.php']);
            exit();
        }

        $user_id = $_SESSION['user_id'];
        $event_id = $_POST['event_id'];

        $checkOwnEventQuery = "SELECT event_manager FROM events WHERE id = $event_id";
        $checkOwnEventResult = $conn->query($checkOwnEventQuery);

        if ($checkOwnEventResult->num_rows > 0) {
            $event_manager = $checkOwnEventResult->fetch_assoc()['event_manager'];
            
            if ($user_id == $event_manager) {
                http_response_code(400); // Bad Request
                echo json_encode(['status' => 'error', 'message' => 'You cannot join your own event.']);
                exit();
            }
        }

        if (!empty($user_id) && !empty($event_id)) {
            $checkQuery = "SELECT * FROM participants WHERE user_id = $user_id AND event_id = $event_id";
            $checkResult = $conn->query($checkQuery);

            if ($checkResult->num_rows == 0) {
                $insertQuery = "INSERT INTO participants (user_id, event_id) VALUES ($user_id, $event_id)";
                $conn->query($insertQuery);
                http_response_code(201); // Created
                echo json_encode(['status' => 'success', 'message' => 'Joined event successfully!']);
            } else {
                http_response_code(400); // Bad Request
                echo json_encode(['status' => 'error', 'message' => 'You already joined this event.']);
            }
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['status' => 'error', 'message' => 'User ID or Event ID not Found']);
        }
        break;

    case 'DELETE': // Adding this case for cancellation
        parse_str(file_get_contents("php://input"), $_DELETE);

        $user_id = $_SESSION['user_id'];
        $event_id = $_DELETE['event_id']; // Assuming the request sends the event_id in the body

        $deleteQuery = "DELETE FROM participants WHERE user_id = $user_id AND event_id = $event_id";

        if ($conn->query($deleteQuery)) {
            http_response_code(200); // OK
            echo json_encode(['status' => 'success', 'message' => 'Attendance canceled successfully!']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['status' => 'error', 'message' => 'Error canceling attendance']);
        }

        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        break;
}

$conn->close();

?>