<?php

require $_SERVER['DOCUMENT_ROOT']."/functions.php";

if (isset($_GET['uid']) && isset($_GET['exhibition_id'])) {
    $qrcheck = new QRCheckClass($_GET['uid'], $_GET['exhibition_id']);

    switch ($qrcheck->get_status($qrcheck->uid)) {
        case 0:
            $qrcheck->insert_path($qrcheck->exhibition_id, 2);
            header("Location: /qr/index.html?exhibition_id={$_GET['exhibition_id']}");
            exit();

        case 1: //退場中
            $previous_exhibition_id = $qrcheck->get_previous_exhibition_id($qrcheck->uid);

            $qrcheck->insert_path($qrcheck->exhibition_id, 2);
            header("Location: /qr/index.html?exhibition_id={$_GET['exhibition_id']}");
            exit();

        case 2: //入場中
            $previous_exhibition_id = $qrcheck->get_previous_exhibition_id($qrcheck->uid);

            if ($previous_exhibition_id != $qrcheck->exhibition_id) {
                header("Location: /qr/select.php?exhibition_id={$_GET['exhibition_id']}&uid={$_GET['uid']}");
                exit();
            }

            $qrcheck->insert_path($qrcheck->exhibition_id, 1);
            header("Location: /qr/index.html?exhibition_id={$_GET['exhibition_id']}");
            exit();
    }
}
