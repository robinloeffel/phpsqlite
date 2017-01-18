<?php
    $reqMethod = $_SERVER['REQUEST_METHOD'];
    $reqUri = $_SERVER['REQUEST_URI'];
    $db = new SQLite3('database.sqlite');

    if ($reqMethod == 'GET') {
        if (preg_match('/^\/api\/names$/i', $reqUri)) {
            $query = 'select * from names';
            $result = $db->query($query);
            $resultArray = [];

            while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                array_push($resultArray, $row);
            }

            http_response_code(200);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($resultArray);
            exit();
        }

        http_response_code(501);
        echo "unknown method {$reqUri}";
        exit();
    }

    if ($reqMethod == 'POST') {
        if (preg_match('/^\/api\/names\/add$/i', $reqUri)) {
            $rawData = json_decode(file_get_contents('php://input'));

            if (count((array)$rawData) !== 3) {
                http_response_code(400);
                echo 'not all required arguments passed';
                exit();
            }

            $name = SQLite3::escapeString($rawData->name);
            $first = SQLite3::escapeString($rawData->first);
            $second = SQLite3::escapeString($rawData->second);
            $third = 'lär';
            $fourth = 'lär';
            $fifth = 'lär';
            $query = "insert into names values (null, '{$name}', '{$first}', '{$second}', '{$third}', '{$fourth}', '{$fifth}')";

            if (!$db->exec($query)) {
                http_response_code(400);
                echo 'an error uccured while adding your data, make sure your data is formatted correctly';
                exit();
            }

            $query = "select * from names where id = {$db->lastInsertRowid()}";
            $result = $db->querySingle($query, true);

            header('Content-Type: application/json; charset=utf-8');
            http_response_code(201);
            echo json_encode($result);
            exit();
        }

        http_response_code(501);
        echo "unknown method {$reqUri}";
        exit();
    }

    if ($reqMethod == 'PUT') {
        if (preg_match('/^\/api\/names\/update\/\d+$/i', $reqUri)) {
            preg_match('/\d+$/', $reqUri, $id);
            $rawData = json_decode(file_get_contents('php://input'));
            $name = SQLite3::escapeString($rawData->name);
            $query = "update names set name = '{$name}' where id = {$id[0]}";

            if (!$db->exec($query)) {
                http_response_code(400);
                echo 'an error uccured while updating your data, make sure your data is formatted correctly';
                exit();
            }

            http_response_code(204);
            exit();
        }

        http_response_code(501);
        echo "unknown method {$reqUri}";
        exit();
    }

    if ($reqMethod == 'DELETE') {
        if (preg_match('/^\/api\/names\/delete$/i', $reqUri)) {
            $query = 'delete from names';
            $result = $db->exec($query);
            http_response_code(204);
            exit();
        }

        if (preg_match('/^\/api\/names\/delete\/\d+$/i', $reqUri)) {
            preg_match('/\d+$/', $reqUri, $id);
            $query = "delete from names where id = {$id[0]}";

            if (!$db->exec($query)) {
                http_response_code(400);
                echo 'an error uccured while deleting your data, make sure your data is formatted correctly';
                exit();
            }

            http_response_code(204);
            exit();
        }

        http_response_code(501);
        echo "unknown method {$reqUri}";
        exit();
    }

    http_response_code(501);
    echo "unknown request type {$reqMethod}";
    exit();
?>
