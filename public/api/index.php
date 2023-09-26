<?php
ini_set('display_errors', "On");

header('Content-Type: application/json; charset=UTF-8');


require "../functions.php";
if (isset($_GET['k'])){
    $db = new DBControlClass();

    switch($_GET['k']) {
        case "exhibition_list":
            $num_list = $db->get_exhibition_list();
            $json_data = json_encode($num_list, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            print $json_data;
            break;

        case "num_list":
            $num_list = $db->get_exhibition_num_list();
            $json_data = json_encode($num_list, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            print $json_data;
            break;
    }
}
