<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *'); // ★ CORS対策: GitHub Pagesなどからのアクセスを許可

// 1. データ取得ロジック (GETリクエスト or POSTリクエストに対応)
$received_uid = '';

// GETリクエスト（URLパラメータとしてUIDを受け取る場合）
if (isset($_GET['uid'])) {
    $received_uid = $_GET['uid'];
} 
// POSTリクエスト（JSONボディとしてUIDを受け取る場合）
elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $received_uid = $data['uid'] ?? '';
}

// 2. 受信したUIDの形式を統一
// URLから来たUID（コロンなし）を、DBの形式（コロンあり）に変換する
// 例: 045319AD330289 → 04:53:19:AD:33:02:89
$normalized_uid = strtoupper($received_uid);
if (strlen($normalized_uid) == 16 && strpos($normalized_uid, ':') === false) {
    // 16文字でコロンがない場合、2文字ごとにコロンを挿入
    $normalized_uid = implode(':', str_split($normalized_uid, 2));
}

// 3. DBの代わりに使用するIDと数字のリスト（配列）を定義 (DB側はコロンありの形式)
$mapping = [
    '04:53:19:AD:33:02:89' => '12345',
    '04:53:19:AD:33:02:90' => '67890',
    // 他のIDと数字をここに追加
];

// 4. 受信した正規化済みUIDがリストに存在するかチェック
if (array_key_exists($normalized_uid, $mapping)) {
    $number_to_display = $mapping[$normalized_uid];
    
    // 成功JSONを返す
    echo json_encode([
        'status' => 'success',
        'uid' => $normalized_uid,
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