<?php
require_once 'config/bootstrap.php';
require_once 'model/mChat.php';

$mChat = new mChat();

// Test: Lấy tin nhắn từ user 5 và 4
echo "📋 Test API chat-file-api.php\n";
echo "=============================\n";

$from = 5;
$to = 4;

// Gọi API
$url = "http://localhost/choviet2912/api/chat-file-api.php?from=$from&to=$to";
echo "URL: $url\n\n";

$response = @file_get_contents($url);
if ($response === false) {
    echo "❌ Không thể gọi API (server chưa chạy)\n";
    echo "Thử lấy trực tiếp từ database:\n\n";
    
    $messages = $mChat->getMessages($from, $to);
    echo json_encode($messages, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) . "\n";
} else {
    echo "✅ Phản hồi từ API:\n";
    echo $response . "\n";
    
    $decoded = json_decode($response, true);
    echo "\n✅ Tổng cộng: " . count($decoded) . " tin nhắn\n";
}
