<?php
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];

include '../connectDB.php';

switch ($method) {
    // display events
    case 'GET':
        $result = $conn->query("SELECT events.*, users.username AS event_manager
        FROM events
        INNER JOIN users ON events.event_manager = users.id
        WHERE events.date_time > NOW()  
        ORDER BY events.date_time ASC  
        LIMIT 3;");

        if (!$result) {
            die("SQL Error: " . $conn->error);
        }

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        echo json_encode($events);
        break;

        // make event
    case 'POST':
        $title = mysqli_real_escape_string($conn, $_POST['title']);
        $description = mysqli_real_escape_string($conn, $_POST['description']);
        $date_time = mysqli_real_escape_string($conn, $_POST['date'] . ' ' . $_POST['time']);
        $event_manager = mysqli_real_escape_string($conn, $_POST['event_manager']);

        if (empty($title) || empty($description) || empty($date_time) || empty($event_manager)) {
            echo json_encode(['status' => 'error', 'message' => 'Fill up all form']);
        } elseif (strtotime($date_time) <= time()) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid date and time. Please choose a future date and time.']);
        } else {
            $conn->query("INSERT INTO events (title, description, date_time, event_manager) VALUES ('$title','$description', '$date_time', '$event_manager')");
            echo json_encode(['status' => 'success', 'message' => 'Event created successfully']);
        }
        break;

        // edit events
    case 'PUT':
        parse_str(file_get_contents("php://input"), $data);
        $id = $data['id'];
        $title = $data['title'];
        $description = $data['description'];
        $date_time = $data['date_time'];

        $conn->query("UPDATE events SET title='$title', description='$description', date_time='$date_time' WHERE id=$id");
        echo json_encode(['message' => 'Event updated successfully']);
        break;

    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = $data['id'];

        $conn->query("DELETE FROM events WHERE id=$id");
        echo json_encode(['message' => 'Event deleted successfully']);
        break;

    // case 'GET':
    //     $result = $conn->query("SELECT events.*, users.username as event_manager FROM events JOIN users ON events.event_manager = users.id");
    //     $events = [];
    //     while ($row = $result->fetch_assoc()) {
    //         $events[] = $row;
    //     }
    //     echo json_encode($events);
    //     break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
        break;
}

$conn->close();
?>