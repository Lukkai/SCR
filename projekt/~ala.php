<?php
    session_start();
    require_once("connect.php");

    $noOfQuestions = 5;
    $_SESSION['q_array'] = getRandomQuiz($noOfQuestions);

    function dumpQuestion($question, $idx)
    {
        echo '<tr><td>Pytanie:</td><td>' . $question['question'] . '</td></tr>';
        echo '<tr><td>Odpowiedz:</td><td> <input type="text" name="answer' . $idx . '" style="width: 100%;"></td></tr>';
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>scr-php</title>
    </head>
    <body>
        <h1> Witaj na stronie Ali! </h1>
        <p>
            <form action="validate_question.php" method="POST">
                <table border=0 cellpadding=10  >
                    <?php
                        for ($i = 0; $i < count($_SESSION['q_array']); $i++)
                        {
                            dumpQuestion($_SESSION['q_array'][$i], $i);
                            echo '<tr><td colspan=2><hr style="width: 100%;"></td></tr>';
                        }
                    ?>
                    <tr><td colspan=2><input type="submit" style="width: 100%;" value="Odpowiedz!"><td></tr>
                </table>
            </form>
        </p>
    </body>
</html>
