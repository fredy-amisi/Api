<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Access-Control-Allow-Origin: http://localhost:3000");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

require_once 'DbConnect.php';
$dbConnect = new DbConnect();
$conn = $dbConnect->conn;
include 'generateToken.php';

function initiatePayment($amount, $phoneNumber) {
    $accessToken = generateToken();
    if (!$accessToken) {
        echo json_encode(['error' => 'Unable to obtain access token']);
        return;
    }

    $shortCode = '174379';
    $passKey = 'bfb279f9aa9bdbcf158e97dd71a467cd2e0c893059b10f78e6b72ada1ed2c919';
    $timestamp = date('YmdHis');
    $password = base64_encode($shortCode . $passKey . $timestamp);

    $url = 'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Bearer '.$accessToken, 'Content-Type: application/json'));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);  // Disable SSL certificate verification
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);  // Disable SSL hostname verification

    $curl_post_data = array(
        'BusinessShortCode' => $shortCode,
        'Password' => $password,
        'Timestamp' => $timestamp,
        'TransactionType' => 'CustomerPayBillOnline',
        'Amount' => $amount,
        'PartyA' => $phoneNumber,
        'PartyB' => $shortCode,
        'PhoneNumber' => $phoneNumber,
        'CallBackURL' => 'YOUR_CALLBACK_URL',
        'AccountReference' => 'Booking123',
        'TransactionDesc' => 'Payment for booking'
    );

    $data_string = json_encode($curl_post_data);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data_string);

    $response = curl_exec($curl);
    $http_code = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    if (curl_errno($curl)) {
        echo 'Curl error: ' . curl_error($curl);
    } else {
        echo 'HTTP code: ' . $http_code . "\n";
        echo 'Response: ' . $response;
    }

    curl_close($curl);

    return json_decode($response);
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if (isset($input['amount']) && isset($input['phoneNumber'])) {
        $amount = $input['amount'];
        $phoneNumber = $input['phoneNumber'];
        $response = initiatePayment($amount, $phoneNumber);
        echo json_encode($response);
    } else {
        echo json_encode(['error' => 'Invalid input data']);
    }
}

