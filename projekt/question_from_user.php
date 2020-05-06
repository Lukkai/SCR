<?php
    session_start();
    require_once("connect.php");

    if (!isset($_POST['username']))
    {
        header("Location: index.php");
        exit();
    }

    $questions = getRandomQuestionFromUser($_POST['username']);
    $q_size = count($questions);

    if ($q_size > 0)
    {
        $q = $questions[rand(0, $q_size - 1)];
        $_SESSION['q_id'] = $q['id'];
        $q_text = $q['question'];
    }
    else
    {
        echo "Error: no questions";
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>scr-php</title>
    </head>
    <body>
        <form action="validate_question.php" method="POST">
            Pytanie: <?php echo $q_text; ?> <br><br>
            Odpowiedz: <input type="text" name="answer"> <br><br>
            <input type="submit" value="Odpowiedz!">
        </form>
    </body>
</html>
