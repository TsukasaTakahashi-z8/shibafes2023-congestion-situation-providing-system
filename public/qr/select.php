<?php
require $_SERVER['DOCUMENT_ROOT']."/functions.php";

if(isset($_GET['uid']) && isset($_GET['exhibition_id'])) {
    if(isset($_POST['re'])) {
        $qrcheck = new QRCheckClass($_GET['uid'], $_GET['exhibition_id']);
        $previous_exhibition_id = $qrcheck->get_previous_exhibition_id($qrcheck->uid);
        switch($_POST['re']) {
            case "2": //入場
                $qrcheck->insert_path($previous_exhibition_id, 1, true);
                $qrcheck->insert_path($qrcheck->exhibition_id, 2);
                header("Location: /qr/index.php?exhibition_id={$_GET['exhibition_id']}");
                exit();

            case "1": //退場
                $qrcheck->insert_path($qrcheck->exhibition_id, 2, true);
                $qrcheck->insert_path($qrcheck->exhibition_id, 1);
                header("Location: /qr/index.php?exhibition_id={$_GET['exhibition_id']}");
                exit();
        }
    } else {
        $res = "
    <main>
        <h1>確認</h1>
        <p>入場と退場を自動で識別できませんでした。入場か退場のどちらかを選択してください。</p>
        <form action=\"\" method=\"POST\">
            <button type=\"submit\" name=\"re\" value=\"2\" id=\"in\">入場</button>
            <button type=\"submit\" name=\"re\" value=\"1\" id=\"out\">退場</button>
        </form>
    </main>
";
    }
} else {
    $res = "<h1>パラメータが指定されていません</h1><p>解決しない場合は、本部までご連絡ください。</p>";
}
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>
<?php echo $res;?>
</body>
</html>
