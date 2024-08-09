<?php
function getRequestData()
{
    return json_decode(file_get_contents('php://input'), true);
}

function sendSuccessResponse($message, $data = [], $statusCode = 200)
{
    http_response_code($statusCode);
    echo json_encode(array_merge(['message' => $message], $data));
}

function sendErrorResponse($message, $statusCode = 400)
{
    http_response_code($statusCode);
    echo json_encode(['message' => $message]);
}

function hashPassword($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

function verifyPassword($password, $hashedPassword)
{
    return password_verify($password, $hashedPassword);
}
?>