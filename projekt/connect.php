<?php
    $host = "localhost";
    $db_user = "root";
    $db_pass = "password";
    $db_name = "scrphp";

    function sum_array($array)
    {
        $result = 0;

        foreach($array as $val)
        {
            if ($val)
                $result += 1;
        }

        return $result;
    }

    function getRandomQuestionsIdx($count, $no)
    {
        $array = array();

        for ($i = 0; $i < $count; $i++)
            array_push($array, false);

        while (sum_array($array) != $no)
            $array[rand(0, $count - 1)] = true;

        $result = array();
        for ($i = 0; $i < $count; $i++)
            if ($array[$i])
                array_push($result, $i);

        shuffle($result);

        return $result;
    }

    function getRandomQuiz($noOfQuestions)
    {
        global $host;
        global $db_user;
        global $db_pass;
        global $db_name;

        $db_connection = @new mysqli($host, $db_user, $db_pass, $db_name);
        $result = false;

        if ($db_connection->connect_errno != 0)
        {
            echo "Error: " . $db_connection->connect_errno;
        }
        else
        {
            $sql_result = $db_connection->query("SELECT * FROM questions");
            if (!$sql_result)
            {
                echo "Error: query not able to execute";
            }
            else if ($sql_result->num_rows == 0)
            {
                echo "Error: user not found";
            }
            else
            {
                $questions = $sql_result->fetch_all(MYSQLI_BOTH);
                $result = array();
                $randIdx = getRandomQuestionsIdx(count($questions), $noOfQuestions);
                foreach ($randIdx as $idx)
                    array_push($result, $questions[$idx]);
            }
            $sql_result->free();
        }
        $db_connection->close();

        return $result;
    }


?>
