<?php
header('Content-Type: application/json');
$data = json_decode(file_get_contents('php://input'), true);

// POSTデータからUIDを取得
$received_uid = $data['uid'] ?? '';

// --- ここで本来はデータベース（DB）に接続し、IDを検索します ---

// DBの代わりに使用するIDと数字のリスト（配列）を定義
$mapping = [
    '04:53:19:AD:33:02:89' => '12345',
    '04:53:19:AD:33:02:90' => '67890',
    // 他のIDと数字をここに追加
];

// 受信したUIDがリストに存在するかチェック
if (array_key_exists($received_uid, $mapping)) {
    // IDが存在する場合、対応する数字を取得
    $number_to_display = $mapping[$received_uid];
    
    // 成功JSONを返す
    echo json_encode([
        'status' => 'success',
        'uid' => $received_uid,
        'number' => $number_to_display
    ]);
} else {
    // IDが存在しない場合、エラーを返す
    echo json_encode([
        'status' => 'error',
        'message' => '指定されたID（' . $received_uid . '）に対応する数字は見つかりませんでした。'
    ]);
}
?>