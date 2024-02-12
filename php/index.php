<?php
session_start();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method){
    case 'GET':
        if (isset($_SESSION['user_id'])) {
            echo json_encode(['success' => true, 'user_id' => $_SESSION['user_id']]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'User not logged in']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['message' => 'Method Not Allowed']);
        break;

}


?>