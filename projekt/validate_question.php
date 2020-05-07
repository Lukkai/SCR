<?php
    function goToIndex()
    {
        header("Location: index.php");
        exit();
    }

    session_start();

    if (!isset($_SESSION['q_array']))
        goToIndex();

    $questionsSize = count($_SESSION['q_array']);
    $nOfGoodAnswers = 0;

    for ($i = 0; $i < $questionsSize; $i++)
    {
        if (!isset($_POST['answer' . $i]))
            goToIndex();

        if ($_POST['answer' . $i] === $_SESSION['q_array'][$i]['answer'])
            $nOfGoodAnswers++;
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>scr-php</title>
    </head>
    <body>
        Liczba dobrych odpowiedzi: <?php echo $nOfGoodAnswers; ?> <br>
        Liczba zlych odpowiedzi: <?php echo $questionsSize - $nOfGoodAnswers; ?> <br>
        Procent: <?php echo ($nOfGoodAnswers / $questionsSize) * 100 . "%"; ?> <br>
        <br><br>
        <a href="index.php">Powrot</a>
    </body>
</html>
