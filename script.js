// NFCリーダーやアプリからこの関数が呼び出されることを想定
function simulateNfcRead() {
    // 実際に読み取ったNFCタグのUIDを設定
    const nfcUID = "04:53:19:AD:33:02:89"; 
    
    // APIへデータを送信
    fetch('api.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify({ uid: nfcUID }), // UIDをJSON形式で送信
    })
    .then(response => response.json())
    .then(data => {
        const displayElement = document.getElementById('display-number');
        
        if (data.status === 'success') {
            // 成功した場合、取得した数字を表示
            displayElement.textContent = data.number;
            displayElement.style.color = '#28a745'; // 緑色
        } else {
            // 失敗した場合、エラーメッセージを表示
            displayElement.textContent = data.message || 'IDが見つかりません';
            displayElement.style.color = '#dc3545'; // 赤色
        }
    })
    .catch((error) => {
        console.error('Error:', error);
        document.getElementById('display-number').textContent = '通信エラーが発生しました';
        document.getElementById('display-number').style.color = '#ffc107';
    });
}

// ページ読み込み時にテスト実行（実際の運用では外部からのトリガーに置き換えます）
// simulateNfcRead();