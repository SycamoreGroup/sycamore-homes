<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(["success" => false, "error" => "Method not allowed"]);
    exit;
}

function clean($value) {
    return htmlspecialchars(trim($value ?? ''), ENT_QUOTES, 'UTF-8');
}

$name         = clean($_POST['name'] ?? '');
$relationship = clean($_POST['relationship'] ?? '');
$email        = clean($_POST['email'] ?? '');
$phone        = clean($_POST['phone'] ?? '');
$meeting      = clean($_POST['meeting'] ?? '');
$message      = clean($_POST['message'] ?? '');

// Basic validation
if ($name === '' || $email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(400);
    echo json_encode(["success" => false, "error" => "Invalid name or email"]);
    exit;
}

$to      = "info@integratedsycamore.com";
$subject = "New Consultation Request from $name";

$body  = "A new consultation request was submitted via the website:\n\n";
$body .= "Name: $name\n";
$body .= "Reaching out as: $relationship\n";
$body .= "Email: $email\n";
$body .= "Phone: $phone\n";
$body .= "Preferred meeting type: $meeting\n";
$body .= "Message:\n$message\n";

$headers   = [];
$headers[] = "From: Sycamore Homes Website <website@integratedsycamore.com>";
$headers[] = "Reply-To: $email";
$headers[] = "Content-Type: text/plain; charset=UTF-8";

$success = mail($to, $subject, $body, implode("\r\n", $headers));

if ($success) {
    echo json_encode(["success" => true]);
} else {
    http_response_code(500);
    echo json_encode(["success" => false, "error" => "Mail could not be sent"]);
}
