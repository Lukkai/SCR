<?php
    session_start();

    if (!isset($_SESSION['logged']) || $_SESSION['logged'] != true)
    {
        header("Location: index.php");
        exit();
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="UTF-8">
        <title>scr-php</title>
    </head>
    <body>
        <h1> Witaj, <?php echo $_SESSION['username']; ?>!</h1> <br><br>

        <form action="add_question.php" method="POST">
            <table border=0 cellpadding=10>
                <tr><td>Pytanie:</td><td><input type="text" name="question"></td></tr>
                <tr><td>Odpowiedź:</td><td><input type="text" name="answer"></td></tr>
            </table>
            <input type="submit" value="Dodaj!">
        </form>
        <br><br>
        <a href="logout.php">Wyloguj</a>
    </body>
</html>
