<?php
    session_start();
    require_once("connect.php");

    if (!isset($_POST['question']) || !isset($_POST['answer']) || !isset($_SESSION['logged']) || $_SESSION['logged'] == false)
    {
        header("Location: index.php");
        exit();
    }

    insertQuestionIntoDb($_SESSION['id'], $_POST['question'], $_POST['answer']);
    header("Location: panel.php");
?>
