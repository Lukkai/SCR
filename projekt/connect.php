<?php
    $host = "localhost";
    $db_user = "root";
    $db_pass = "password";
    $db_name = "scrphp";

    function getAllUsers()
    {
        global $host;
        global $db_user;
        global $db_pass;
        global $db_name;

        $db_connection = @new mysqli($host, $db_user, $db_pass, $db_name);
        $result = false;

        if ($db_connection->connect_errno != 0)
        {
            echo "Error: " . $db_connection->connect_errno . " Opis: " . $db_connection->connect_error;
        }
        else
        {
            $sql_result = $db_connection->query("SELECT * FROM users");
            $result = $sql_result->fetch_all(MYSQLI_BOTH);
            $sql_result->free();
        }
        $db_connection->close();

        return $result;
    }

    function getRandomQuestionFromUser($username)
    {
        global $host;
        global $db_user;
        global $db_pass;
        global $db_name;

        $db_connection = @new mysqli($host, $db_user, $db_pass, $db_name);
        $result = false;

        if ($db_connection->connect_errno != 0)
        {
            echo "Error: " . $db_connection->connect_errno . " Opis: " . $db_connection->connect_error;
        }
        else
        {
            $sql_result = $db_connection->query("SELECT * FROM users WHERE username='$username'");
            if ($sql_result->num_rows != 1)
            {
                echo "Error: user not found";
            }
            else
            {
                $assoc = $sql_result->fetch_assoc();
                $id = $assoc['id'];
                $sql_result_question = $db_connection->query("SELECT * FROM questions WHERE user_id=" . $id);
                $result = $sql_result_question->fetch_all(MYSQLI_BOTH);
                $sql_result_question->free();
            }
            $sql_result->free();
        }
        $db_connection->close();

        return $result;
    }

    function getQuestionFromId($id)
    {
        global $host;
        global $db_user;
        global $db_pass;
        global $db_name;

        $db_connection = @new mysqli($host, $db_user, $db_pass, $db_name);
        $result = false;

        if ($db_connection->connect_errno != 0)
        {
            echo "Error: " . $db_connection->connect_errno . " Opis: " . $db_connection->connect_error;
        }
        else
        {
            $sql_result = $db_connection->query("SELECT * FROM questions WHERE id=" . $id);
            if ($sql_result->num_rows != 1)
            {
                echo "Error: question not found";
            }
            else
            {
                $result = $sql_result->fetch_assoc();
            }
            $sql_result->free();
        }
        $db_connection->close();

        return $result;
    }

    function insertQuestionIntoDb($user_id, $question, $answer)
    {
        global $host;
        global $db_user;
        global $db_pass;
        global $db_name;

        $db_connection = @new mysqli($host, $db_user, $db_pass, $db_name);
        if ($db_connection->connect_errno != 0)
        {
            echo "Error: " . $db_connection->connect_errno . " Opis: " . $db_connection->connect_error;
        }
        else
        {
            $sql_result = $db_connection->query(
                sprintf("INSERT INTO questions(user_id, question, answer) VALUES ('%s', '%s', '%s')",
                    $user_id,
                    $question,
                    $answer));
        }
        $db_connection->close();
    }
?>
