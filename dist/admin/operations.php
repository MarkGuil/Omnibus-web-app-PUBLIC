<?php

include('../database/db.php');

if ($_SERVER["REQUEST_METHOD"] == "GET") {
    extract($_GET);
    if (!isset($operation)) {
        echo "error";
        exit;
    }

    //you can have multiple operations here, i'll use switch to facilitate future codes
    switch ($operation) {
        case 'deletecompany':
            $sql = "DELETE FROM `user_partner_admin` WHERE `id`=$id";
            $res = $conn->query($sql);
            if ($res === true) {
                echo "ok";
                exit;
            } else {
                echo $dbcon->error;
                exit;
            }
            break;
    }
}
