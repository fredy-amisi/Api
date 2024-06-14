<?php
require_once 'DbConnect.php';


$token = $_GET['token'];

$stmt = $pdo->prepare('SELECT * FROM admin_invites WHERE token = ?');
$stmt->execute([$token]);
$invite = $stmt->fetch();

$response = ['valid' => false];

if ($invite && new DateTime() <= new DateTime($invite['expires'])) {
    $response['valid'] = true;
}

echo json_encode($response);
?>
