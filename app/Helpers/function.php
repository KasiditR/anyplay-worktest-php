<?php
function get_request_data()
{
    return json_decode(file_get_contents('php://input'), true);
}

function send_success_response($message, $data = [], $status_code = 200)
{
    http_response_code($status_code);
    echo json_encode(array_merge(['message' => $message], $data));
}

function send_error_response($message, $status_code = 400)
{
    http_response_code($status_code);
    echo json_encode(['message' => $message]);
}

function hash_password($password)
{
    return password_hash($password, PASSWORD_BCRYPT);
}

function verify_password($password, $hashed_password)
{
    return password_verify($password, $hashed_password);
}
?>