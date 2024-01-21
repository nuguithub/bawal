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
        $eventId = $_GET['event_id'];
    
        $result = $conn->query("SELECT 
            events.title,
            profile.*
        FROM participants
        LEFT JOIN profile ON participants.user_id = profile.account_id
        INNER JOIN events ON participants.event_id = events.id
        WHERE participants.event_id = $eventId");
    
        if (!$result) {
            die("SQL Error: " . $conn->error);
        }
    
        $participants = [];
        while ($row = $result->fetch_assoc()) {
            $participants[] = $row;
        }
    
        echo json_encode($participants);
        break;
    

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

$conn->close();
?>