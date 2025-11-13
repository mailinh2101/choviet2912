<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/mLoginLogout.php';

$model = new mLoginLogout();
$contact = 'test-otp@example.com';
$otp = $model->createOTP($contact, 'email');
if ($otp) {
    echo "Created OTP: $otp\n";
} else {
    echo "Failed to create OTP\n";
}
?>