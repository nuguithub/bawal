<?php
session_start();

header('Content-Type: application/json');

include '../connectDB.php';

$user_id = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;

if (!$user_id) {
    http_response_code(401); // Unauthorized
    echo json_encode(['error' => 'User not authenticated']);
    exit;
}

$fetchProfileQuery = "SELECT * FROM profile WHERE account_id = ?";
$stmtFetchProfile = $conn->prepare($fetchProfileQuery);
$stmtFetchProfile->bind_param('i', $user_id);
$stmtFetchProfile->execute();
$result = $stmtFetchProfile->get_result();

if ($result->num_rows > 0) {
    $profileData = $result->fetch_assoc();
    echo json_encode($profileData);
} else {
    http_response_code(404); 
    echo json_encode(['error' => 'Profile not found']);
}

$stmtFetchProfile->close();
$conn->close();
?>