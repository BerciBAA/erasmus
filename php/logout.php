<?php
session_start();

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    case 'POST':
        session_unset();

        session_destroy();

        echo json_encode(['success' => true, 'message' => 'Successfully logged out']);
        break;
    
    default:
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
        break;
}

exit();