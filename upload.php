<?php
    $target_dir = "imgs/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    if(isset($_POST["upload"])) {
        $check = getimagesize($_FILES["file"]["tmp_name"]);

        if($check !== false) {
            if(move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                echo 1;
            } else {
                echo 0;
            }
        } else {
            echo 0;
        }
    }
?>