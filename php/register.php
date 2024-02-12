<?php
header("Content-Type: application/json");

$method = $_SERVER['REQUEST_METHOD'];


switch ($method) {
    case 'POST':
        require_once 'database.php';
        $data = json_decode(file_get_contents('php://input'), true);
        if (empty($data['firstName']) || empty($data['secondName']) || empty($data['emailAddress']) || empty($data['password']) || empty($data['confirmPassword']) || empty($data['schoolId'])) {
            http_response_code(400);
            echo json_encode(['message' => 'All fields are required.']);
            exit;
        }

        if (!filter_var($data['emailAddress'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['message' => 'Invalid email format.']);
            exit;
        }

        if ($data['password'] !== $data['confirmPassword']) {
            http_response_code(400);
            echo json_encode(['message' => 'Passwords do not match.']);
            exit;
        }

        $schoolId = $conn->real_escape_string($data['schoolId']);
        $checkSchoolId = $conn->prepare("SELECT id FROM schools WHERE id = ?");
        $checkSchoolId->bind_param("i", $schoolId);
        $checkSchoolId->execute();
        $checkSchoolId->store_result();
        
        if ($checkSchoolId->num_rows == 0) {
            http_response_code(400);
            echo json_encode(['message' => 'The provided school ID does not exist.'. $schoolId]);
            exit;
        }
        $checkSchoolId->close();

        $emailAddress = $conn->real_escape_string($data['emailAddress']);
        $checkEmail = $conn->prepare("SELECT id FROM users WHERE email = ?");
        $checkEmail->bind_param("s", $emailAddress);
        $checkEmail->execute();
        $checkEmail->store_result();
        if ($checkEmail->num_rows > 0) {
            http_response_code(409);
            echo json_encode(['message' => 'Email address already exists.']);
            exit;
        }
        $checkEmail->close();

        $firstName = $conn->real_escape_string($data['firstName']);
        $secondName = $conn->real_escape_string($data['secondName']);
        $emailAddress = $conn->real_escape_string($data['emailAddress']);
        $password = $conn->real_escape_string($data['password']);
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $conn->prepare("INSERT INTO users (first_name, second_name, email, password, school_id) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $firstName, $secondName, $emailAddress, $passwordHash, $schoolId);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(['message' => 'User successfully created.']);
        } else {
            http_response_code(500);
            echo json_encode(['message' => 'Failed to create user: ' . $stmt->error]);
        }

        $stmt->close();
        break;

    case 'GET':
        require_once 'database.php';
        $sql = "SELECT id, name FROM schools";
        $result = $conn->query($sql);

        $schools = [];
        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                $schools[] = $row;
            }
        }

        echo json_encode($schools);
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed.']);
        break;
}
?>