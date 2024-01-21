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
        $title = $_POST['title'];
        $description = $_POST['description'];
        $venue = $_POST['venue'];
        $date = $_POST['date'];
        $time = $_POST['time'];

        $date_time = $date . ' ' . $time;

        // Fetch the old image path before updating
        $oldImagePath = "";
        if (!empty($event_id)) {
            $stmt = $conn->prepare("SELECT image FROM events WHERE id=?");
            $stmt->bind_param("i", $event_id);
            $stmt->execute();
            $stmt->bind_result($oldImagePath);
            $stmt->fetch();
            $stmt->close();
        }

        // Validate and process the uploaded image
        if (!empty($_FILES["image"]["name"])) {
            $targetDir = "../images/";
            $imageFileType = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
            $targetFile = $targetDir . generateUniqueFileName(32) . "." . $imageFileType;

            $check = getimagesize($_FILES["image"]["tmp_name"]);
            if ($check === false) {
                echo json_encode(['status' => 'error', 'message' => 'File is not an image or unsupported image format.']);
                exit();
            }

            if (move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile)) {
                // Update the database with the new image path
                $stmt = $conn->prepare("UPDATE events SET title=?, description=?, venue=?, image=?, date_time=? WHERE id=?");
                $stmt->bind_param("sssssi", $title, $description, $venue, $targetFile, $date_time, $event_id);
                $stmt->execute();
                $stmt->close();

                // Delete the old image file
                if (!empty($oldImagePath) && file_exists($oldImagePath) && !is_dir($oldImagePath)) {
                    unlink($oldImagePath);
                }
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Failed to move the uploaded image.']);
                exit();
            }
        } else {
            // No new image uploaded, update the database without changing the image
            $stmt = $conn->prepare("UPDATE events SET title=?, description=?, venue=?, date_time=? WHERE id=?");
            $stmt->bind_param("ssssi", $title, $description, $venue, $date_time, $event_id);
            $stmt->execute();
            $stmt->close();

            // Delete the old image file if it exists
            if (!empty($oldImagePath) && file_exists($oldImagePath) && !is_dir($oldImagePath)) {
                unlink($oldImagePath);
            }
        }


        echo json_encode(['status' => 'success', 'message' => 'Event updated successfully']);
        break;

    default:
        http_response_code(405); // Method Not Allowed
        echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
        break;
}

function generateUniqueFileName($length) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
}
?>