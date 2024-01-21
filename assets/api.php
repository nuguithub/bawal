<?php
header('Content-Type: application/json');

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
            die(json_encode(['status' => 'error', 'message' => 'SQL Error: ' . $conn->error]));
        }

        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        echo json_encode($events);
        break;

    // make event
    case 'POST':
        $title = $_POST['title'];
        $description = $_POST['description'];
        $venue = $_POST['venue'];
        $date_time = $_POST['date'] . ' ' . $_POST['time'];
        $event_manager = $_POST['event_manager'];

        if (empty($title) || empty($description) || empty($venue) || empty($date_time)) {
            echo json_encode(['status' => 'error', 'message' => 'Fill up all form']);
        } elseif (strtotime($date_time) <= time()) {
            echo json_encode(['status' => 'error', 'message' => 'Invalid date and time. Please choose a future date and time.']);
        } else {
            
            $uploadedImage = $_FILES['image'];

            $imageName = generateRandomFilename() . '_' . basename($uploadedImage['name'], PATHINFO_EXTENSION);

            // Check if the file extension is valid
            if (!in_array(strtolower(pathinfo($imageName, PATHINFO_EXTENSION)), array('jpg', 'jpeg', 'png'))) {
                $response = array('success' => 'error', 'message' => 'Invalid file format. Only JPG, JPEG, and PNG allowed.');
                header('Content-Type: application/json');
                echo json_encode($response);
                exit;
            }
            
            $imagePath = '../images/' . $imageName;
            move_uploaded_file($uploadedImage['tmp_name'], $imagePath);

            $stmt = $conn->prepare("INSERT INTO events (title, description, venue, date_time, event_manager, image) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $title, $description, $venue, $date_time, $event_manager, $imageName);

            if ($stmt->execute()) {
                echo json_encode(['status' => 'success', 'message' => 'Event created successfully']);
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to insert event into the database: ' . $stmt->error]);
            }
            
        }
        
    break;
        
    
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $data);
        $id = $data['id'];

        $conn->query("DELETE FROM events WHERE id=$id");
        echo json_encode(['message' => 'Event deleted successfully']);
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
    break;
}

function generateRandomFilename() {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < 32; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}

$conn->close();
?>