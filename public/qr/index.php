<?php
require $_SERVER['DOCUMENT_ROOT']."/functions.php";

if (empty($_GET['result'])) {
    $qrcheck = new QRCheckClass(null, $_GET['exhibition_id']);
    $title = $qrcheck->get_exhibition_list()[$qrcheck->exhibition_id]['title'];
    if (empty($title)) {
        $title = "企画IDが異なります。<br>メールアドレスから、正しいURLを開いてください。";
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>出展団体用QRコードリーダ</title>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script> 
    <link href="https://fonts.googleapis.com/css?family=Ropa+Sans" rel="stylesheet">
    <link href="./style.css" rel="stylesheet">
    <?php
        if ($_GET['result'] == "in"){
            echo "<script type='text/javascript'>alert('入場処理完了')</script>";
            $title = "引き続きスキャンしてください。";
        }
        if ($_GET['result'] == "out"){
            echo "<script type='text/javascript'>alert('退場処理完了')</script>";
            $title = "引き続きスキャンしてください。";
        }
?>
</head>
<body>
    <div id="particles-js"></div>
    <h1>来場者統計システム</h1>
    <h2><?php echo $title; ?></h2>
    <div id="loadingMessage">🎥 カメラのアクセスが許可されていません。<br> (このサイトのカメラへのアクセスを許可してください。)<br>許可をしてもこの表示が出る場合は、他のアプリケーションでカメラが使用されていないか、確認してください。</div>
    <canvas id="canvas" hidden></canvas>
    <div id="output" hidden>
        <div id="outputMessage">来場者のパンフレット裏にある、QRコードをかざしてください。</div>
        <div hidden><span id="outputDate">Data:</span>&emsp; <span id="outputData"></span></div>
    </div>
    <script>
        var video = document.createElement("video");
        var canvasElement = document.getElementById("canvas");
        var canvas = canvasElement.getContext("2d");
        var loadingMessage = document.getElementById("loadingMessage");
        var outputContainer = document.getElementById("output");
        var outputMessage = document.getElementById("outputMessage");
        var outputData = document.getElementById("outputData");
        var outputElement = document.getElementById("output")

        function drawLine(begin, end, color) {
            canvas.beginPath();
            canvas.moveTo(begin.x, begin.y);
            canvas.lineTo(end.x, end.y);
            canvas.lineWidth = 4;
            canvas.strokeStyle = color;
            canvas.stroke();
        }

        // Use facingMode: environment to attemt to get the front camera on phones
        navigator.mediaDevices.getUserMedia({video: {facingMode: "environment"}}).then(function (stream) {
            video.srcObject = stream;
            video.setAttribute("playsinline", true); // required to tell iOS safari we don't want fullscreen
            video.play();
            requestAnimationFrame(tick);
        });

        function tick() {
            loadingMessage.innerText = "⌛ Loading video..."
            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                loadingMessage.hidden = true;
                canvasElement.hidden = false;
                outputContainer.hidden = false;

                canvasElement.height = video.videoHeight;
                canvasElement.width = video.videoWidth;
                canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                var code = jsQR(imageData.data, imageData.width, imageData.height, {
                    inversionAttempts: "dontInvert",
                });
                if (code) {
                    outputMessage.hidden = true;
                    if (code.data.substr(0, 53) == "https://shibafufes68th.main.jp/vote/voteform.php?uid=") {
                        window.location.href = "./confirm.php" + location.search + "&uid=" + code.data.substr(53);
                    }
                } else {
                    outputMessage.hidden = false;
                    outputData.parentElement.hidden = true;
                }
            }
            requestAnimationFrame(tick);
        }
    </script>
</body>
</html>
