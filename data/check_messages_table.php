<?php
require_once 'config/bootstrap.php';
require_once 'model/mConnect.php';

$mConnect = new Connect();
$conn = $mConnect->connect();

$result = $conn->query("DESCRIBE messages;");
echo "📋 Cấu trúc bảng messages:\n";
echo "================================\n";

while($row = $result->fetch_assoc()) {
    echo $row['Field'] . " - " . $row['Type'];
    if ($row['Null'] === 'NO') echo " (NOT NULL)";
    if ($row['Key'] === 'PRI') echo " (PRIMARY KEY)";
    echo "\n";
}

echo "\n✅ Xong\n";
