<?php
ini_set('display_errors', "On");
require "../functions.php";

if (isset($_GET['uid']) && isset($_GET['exhibition_id'])) {
    $db = new DBControlClass();

    $uidClass = new UidClass($_POST['uid']);
    $uid = $uidClass->get_id();

    echo $db->get_status($uid);
    switch ($db->get_status($uid)) {
    case 0:
        // 入場処理
    case 1:
        $previous_exhibition_id = $db->get_previous_exhibition_id($uid);
        if ($previous_exhibition_id == $exhibition_id) {
        }
    case 2:
    }
}
