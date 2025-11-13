<?php
require_once 'config/bootstrap.php';
require_once 'model/mChat.php';

$mChat = new mChat();

// Test: Gửi tin nhắn từ user 5 đến user 4
echo "📨 Test gửi tin nhắn...\n";
echo "======================\n";

$from = 5;
$to = 4;
$content = "Xin chào, tin nhắn test từ user 5 - " . date('Y-m-d H:i:s');
$product_id = 0;

$result = $mChat->sendMessage($from, $to, $content, $product_id);

if ($result) {
    echo "✅ Tin nhắn đã được lưu thành công!\n";
} else {
    echo "❌ Lỗi khi lưu tin nhắn\n";
}

// Kiểm tra tin nhắn trong database
echo "\n📋 Lấy danh sách tin nhắn giữa user 5 và 4:\n";
echo "=========================================\n";

$messages = $mChat->getMessages($from, $to);

if (count($messages) > 0) {
    foreach ($messages as $msg) {
        $sender = ($msg['sender_id'] == $from) ? 'User 5' : 'User 4';
        echo "[" . $msg['created_time'] . "] $sender: " . substr($msg['content'], 0, 50) . "...\n";
    }
    echo "\n✅ Tổng cộng: " . count($messages) . " tin nhắn\n";
} else {
    echo "❌ Không tìm thấy tin nhắn nào\n";
}

// Kiểm tra danh sách người dùng có cuộc trò chuyện
echo "\n👥 Danh sách người dùng có cuộc trò chuyện với user 5:\n";
echo "====================================================\n";

$conversations = $mChat->getConversationUsers(5);

if (count($conversations) > 0) {
    foreach ($conversations as $user) {
        echo "- " . $user['username'] . " (ID: " . $user['id'] . ")\n";
        echo "  Tin cuối: " . $user['tin_cuoi'] . "\n";
        echo "  Lúc: " . $user['created_time'] . "\n";
    }
} else {
    echo "❌ Không tìm thấy cuộc trò chuyện nào\n";
}

echo "\n✅ Test xong\n";
