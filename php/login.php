<?php
session_start();

header('Content-Type: application/json');
require_once 'database.php';
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        if (empty($data['emailAddress']) || empty($data['password'])) {
            http_response_code(400);
            echo json_encode(['message' => 'Please fill out all fields.']);
            exit;
        }

        $emailAddress = $data['emailAddress'];
        $password = $data['password'];

        $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
        $stmt->bind_param("s", $emailAddress);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                echo json_encode(['message' => 'Login successful.']);
            } else {
                http_response_code(401);
                echo json_encode(['message' => 'Incorrect password.']);
            }
        } else {
            http_response_code(404);
            echo json_encode(['message' => 'User not found.']);
        }

        $stmt->close();
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;
}

$conn->close();
?>