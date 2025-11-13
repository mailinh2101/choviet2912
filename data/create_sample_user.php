<?php
require_once __DIR__ . '/../config/bootstrap.php';

$host = getenv('DB_HOST') ?: '127.0.0.1';
$port = getenv('DB_PORT') ?: 3306;
$user = getenv('DB_USER') ?: 'root';
$pass = getenv('DB_PASS') ?: '';
$db   = getenv('DB_NAME') ?: 'test';

$mysqli = mysqli_init();
mysqli_options($mysqli, MYSQLI_OPT_CONNECT_TIMEOUT, 5);
if (!@mysqli_real_connect($mysqli, $host, $user, $pass, $db, (int)$port)) {
    die("DB connect failed: " . mysqli_connect_error() . "\n");
}

// Sample credentials (change if you want)
$username = 'sample_user';
$email = 'sample_user@example.com';
$passwordPlain = 'P@ssw0rd123';
$role_id = 2; // regular user
$is_active = 1;
$is_verified = 1;

// Check existing
$stmt = $mysqli->prepare('SELECT id FROM users WHERE email = ? OR username = ?');
$stmt->bind_param('ss', $email, $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo "A user with that email or username already exists.\n";
    $stmt->close();
    exit(0);
}
$stmt->close();

$hashed = password_hash($passwordPlain, PASSWORD_BCRYPT);
$created_date = date('Y-m-d');
$updated_date = date('Y-m-d H:i:s');

$balance = 0.00;
// Build and execute safe query
$query = "INSERT INTO users (username, email, password, role_id, account_type, business_verified, created_date, updated_date, is_active, is_verified, balance) VALUES (?, ?, ?, ?, 'ca_nhan', 0, ?, ?, ?, ?, ?)";
$stmt2 = $mysqli->prepare($query);
if (!$stmt2) {
    die("Prepare failed: " . $mysqli->error . "\n");
}
$stmt2->bind_param('sssissiid', $username, $email, $hashed, $role_id, $created_date, $updated_date, $is_active, $is_verified, $balance);
if (!$stmt2->execute()) {
    die("Insert failed: " . $stmt2->error . "\n");
}
$insertedId = $stmt2->insert_id;
$stmt2->close();

echo "Sample user created:\n";
echo "  id: $insertedId\n";
echo "  username: $username\n";
echo "  email: $email\n";
echo "  password (plain): $passwordPlain\n";

$mysqli->close();
