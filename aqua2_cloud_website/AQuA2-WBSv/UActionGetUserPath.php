<?php
session_start();
if (isset($_SESSION['user_current_FM_path'])) {
    echo json_encode(array("success" => true, "path" => $_SESSION['user_current_FM_path']));
} else {
    echo json_encode(array("success" => false, "error" => "Path not set."));
}
?>