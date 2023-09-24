<?php
ini_set('display_errors', "On");
require "../functions.php";

if (isset($_GET['uid'])) {
    $db = new DBControlClass();

    //$uidClass = new UidClass($_POST['uid']);
    //$uid = $uidClass->get_id();
    $uid = $_GET['uid'];
    echo $db->get_status($uid);
    switch ($db->get_status($uid)) {
    case 0:
        // 入場処理
    case 1:
        $db->get_previous_exhibition_id($uid);
        if (/*前のexhibitionid*/true) {

        }
    }
}
