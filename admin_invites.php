<?php
// send_invite.php
session_start();

require_once 'DbConnect.php';


if ($_SESSION['role'] !== 'admin') {
    die('Only admins can send invitations.');
}

$email = $_POST['email'];
$token = bin2hex(random_bytes(16));
$expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

$stmt = $pdo->prepare('INSERT INTO admin_invites (email, token, expires) VALUES (?, ?, ?)');
$stmt->execute([$email, $token, $expires]);

$inviteLink = "http://localhost:3000/admin_signup.php?token=$token";
mail($email, 'Admin Invitation', "Sign up here: $inviteLink");

echo 'Invitation sent';
?>
