<?php
    session_start();
    require_once("connect.php");

    if (!isset($_POST['answer']) || !isset($_SESSION['q_id']))
    {
        header("Location: index.php");
        exit();
    }

    $question = getQuestionFromId($_SESSION['q_id']);
    unset($_SESSION['q_id']);

    if ($_POST['answer'] == $question['answer'])
        header("Location: win.php");
    else
        header("Location: lose.php");
?>
