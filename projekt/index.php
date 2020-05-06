<?php
    session_start();
    require_once("./connect.php");

    if (isset($_SESSION['logged']) && $_SESSION['logged'] == true)
    {
        header("Location: panel.php");
        exit();
    }
?>
<!DOCTYPE HTML>
<html lang="pl">
    <head>
        <meta charset="utf-8">
        <title>scr-php</title>
    </head>
    <script>
        function main() {
            for (form_el in ["username", "password"])
                document.log_in_form[form_el].addEventListener("keypress", ifEnterThen(loginFormSubmit));
        }

        function loginFormSubmit() {
            var name = document.log_in_form["username"].value;
            var password = document.log_in_form["password"].value;

            if (name == "" || password == "")
                document.getElementById("error_msg_span").innerHTML = "Login i/lub hasło nie mogą być puste!";
            else
                document.log_in_form.submit();
        }

        function ifEnterThen(func) {
            return function(e)
                {
                    if (e.code == "Enter")
                        func();
                }
        }

        function sentUsername(username) {
            document.question_form["username"].value = username;
            document.question_form.submit();
        }

    </script>
    <body>
        <p>
            <form method="POST" action="    login.php" name="log_in_form">
                Login: <input type="text" name="username"> <br>
                Haslo: <input type="password" name="password"> <br> <br>
                <input type="button" value="zaloguj" onclick="loginFormSubmit();"> <br> <br>

                <span style="color: red;" id=error_msg_span>
                <?php
                    if (isset($_SESSION['error']))
                        echo $_SESSION['error'];
                ?>
                </span>
            </form>
        </p>
        <br><br>
        <p>
            Lista uzytkownikow:
            <form method="POST" action="question_from_user.php" name="question_form">
                <input type="hidden" name="username">
                <table border=1 cellpadding=10>
                    <tr><td> id </td> <td> nazwa </td><td></td></tr>
                    <?php
                        $users = getAllUsers();

                        foreach ($users as $user)
                        {
                            echo "<tr><td>" . $user['id'] . "</td><td>" . $user['username'] . "</td>";
                            echo '<td><input type="button" value="Pytanie" onclick=sentUsername("' . $user['username'] . '")></td></tr>';
                        }
                    ?>
                </table>
            </form>
        </p>
        <script>
            main();
        </script>
    </body>
</html>
