<?php
    $reqMethod = $_SERVER['REQUEST_METHOD'];
    $db = new SQLite3('database.sqlite');

    if ($reqMethod == 'GET') {
        $query = 'select * from names';
        $result = $db->query($query);
        $resultArray = array();

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            array_push($resultArray, $row);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultArray);
    }

    if ($reqMethod == 'POST') {
        $rawPost = json_decode(file_get_contents('php://input'));

        $name = SQLite3::escapeString($rawPost->name);
        $first = SQLite3::escapeString($rawPost->first);
        $second = SQLite3::escapeString($rawPost->second);
        $third = 'lär';
        $fourth = 'lär';
        $fifth = 'lär';

        $query = "insert into names values (null, '{$name}', '{$first}', '{$second}', '{$third}', '{$fourth}', '{$fifth}')";
        $db->query($query) or die('no');

        $query = 'select * from names order by id desc limit 1';
        $result = $db->query($query);

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($result->fetchArray(SQLITE3_ASSOC));
    }

    if ($reqMethod == 'PUT') {
        echo 'put';
    }

    if ($reqMethod == 'DELETE') {
        $query = 'delete from names';
        $result = $db->query($query);
        $resultArray = array();

        while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
            array_push($resultArray, $row);
        }

        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($resultArray);
    }
?>
