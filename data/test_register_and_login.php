<?php
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../model/mLoginLogout.php';

$model = new mLoginLogout();
$username = 'cli_test_user_' . rand(1000,9999);
$email = 'cli_test_' . rand(1000,9999) . '@example.com';
$password = 'TestP@ssw0rd!';

echo "Registering user $username / $email\n";
$hash = password_hash($password, PASSWORD_BCRYPT);
$ok = $model->registerUser($username, $email, '', $hash, 1);
if ($ok) {
    echo "Registered OK. Now fetching user and verifying password...\n";
    $user = $model->getUserByIdentifier($email);
    if ($user) {
        echo "Stored password length: " . strlen($user['password']) . "\n";
        if (password_verify($password, $user['password'])) {
            echo "Password_verify OK\n";
        } else {
            echo "Password_verify FAILED\n";
        }
    } else {
        echo "Could not fetch user after register\n";
    }
} else {
    echo "Register failed\n";
}
?>