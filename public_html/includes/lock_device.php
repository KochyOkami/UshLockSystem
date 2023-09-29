<?php
session_start();

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    include 'functions.php';

    $lock = getLocksById($id);
    if (count($lock) > 0) {
        $lock = $lock[0];

        $result = updateLockStatus($lock['id'], true);
        if ($result) {
            echo $result;
        }
        header("Location: ../lock?id=".$lock['id']);
        die;
    } else {
        header("Location: ../index");
        die;
    }

} else {
    header("Location: ../index");
    die;
}
