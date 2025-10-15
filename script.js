document.addEventListener('DOMContentLoaded', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const nfcUID = urlParams.get('tagid'); // URLから 'tagid' パラメータを取得

    const displayElement = document.getElementById('display-number');
    
    if (nfcUID) {
        // UID（tagid）がURLに含まれている場合、サーバーAPIへPOST送信
        fetch('api.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ uid: nfcUID }),
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                displayElement.textContent = data.number;
                displayElement.style.color = '#28a745';
            } else {
                displayElement.textContent = data.message || 'IDが見つかりません';
                displayElement.style.color = '#dc3545';
            }
        })
        .catch((error) => {
            console.error('通信エラー:', error);
            displayElement.textContent = '通信エラーが発生しました';
        });
    } else {
        // URLにtagidが含まれていない場合
        displayElement.textContent = 'NFCタグからの読み取り情報が見つかりません。';
    }
});