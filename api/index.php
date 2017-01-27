<?php
    $reqMethod = $_SERVER['REQUEST_METHOD'];
    $reqUri = $_SERVER['REQUEST_URI'];
    $db = new SQLite3('names.db');

    if ($reqMethod == 'GET') {
        if (preg_match('/\/api\/names$/i', $reqUri)) {
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
        if (preg_match('/\/api\/names$/i', $reqUri)) {
            $rawData = json_decode(file_get_contents('php://input'));

            if (count((array)$rawData) !== 3) {
                http_response_code(400);
                echo 'not all required arguments passed';
                exit();
            }

            $stmt = $db->prepare('insert into names values (null, :name, :first, :second, :third, :fourth, :fifth)');
            $stmt->bindValue(':name', $rawData->name, SQLITE3_TEXT);
            $stmt->bindValue(':first', $rawData->first, SQLITE3_TEXT);
            $stmt->bindValue(':second', $rawData->second, SQLITE3_TEXT);
            $stmt->bindValue(':third', '', SQLITE3_TEXT);
            $stmt->bindValue(':fourth','', SQLITE3_TEXT);
            $stmt->bindValue(':fifth', '', SQLITE3_TEXT);

            if (!$stmt->execute()) {
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
        if (preg_match('/\/api\/names\/\d+$/i', $reqUri)) {
            preg_match('/\d+$/', $reqUri, $id);
            $rawData = json_decode(file_get_contents('php://input'));

            $stmt = $db->prepare('update names set name = :name where id = :id');
            $stmt->bindValue(':name', $rawData->name, SQLITE3_TEXT);
            $stmt->bindValue(':id', $id[0], SQLITE3_INTEGER);

            if (!$stmt->execute()) {
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
        if (preg_match('/\/api\/names$/i', $reqUri)) {
            $query = 'delete from names';
            $result = $db->exec($query);
            http_response_code(204);
            exit();
        }

        if (preg_match('/\/api\/names\/\d+$/i', $reqUri)) {
            preg_match('/\d+$/', $reqUri, $id);

            $stmt = $db->prepare('delete from names where id = :id');
            $stmt->bindValue(':id', $id[0], SQLITE3_INTEGER);

            if (!$stmt->execute()) {
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
