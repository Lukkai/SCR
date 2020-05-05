<?php
    session_start();
    require_once("connect.php");

    if (!isset($_POST['username']) || !isset($_POST['password']))
    {
        header("Location: index.php");
        exit();
    }

    $db_connection = @new mysqli($host, $db_user, $db_pass, $db_name);

    if ($db_connection->connect_errno != 0)
    {
        echo "Error: " . $db_connection->connect_errno;
    }
    else
    {
        $login = $_POST['username'];
        $password = $_POST['password'];

        // sanitize user's input
        $login = htmlentities($login, ENT_QUOTES, "UTF-8");
        $password = htmlentities($password, ENT_QUOTES, "UTF-8");

        $sql_query = sprintf(
            "SELECT * FROM users WHERE username='%s' AND password='%s'",
            mysqli_real_escape_string($db_connection, $login),
            mysqli_real_escape_string($db_connection, $password));
        $sql_result = @$db_connection->query($sql_query);

        if ($sql_result)
        {
            if ($sql_result->num_rows == 1)
            {
                unset($_SESSION['error']);

                // what if more than one in num_rows ?
                // zelent says first will be fetched...
                $data = $sql_result->fetch_assoc();

                // input data obtained from databse into session
                $_SESSION['logged'] = true;
                $_SESSION['id'] = $data['id'];
                $_SESSION['username'] = $data['username'];

                $sql_result->free();
                header("Location: ./panel.php");
            }
            else
            {
                $_SESSION['error'] = 'Username and/or password wrong';
                header("Location: ./index.php");
            }
        }
        $db_connection->close();
    }
?>
