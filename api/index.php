<?php
    require_once 'Request.php';
    $request = new Request($_SERVER);
    $db = new SQLite3('names.db');

    if ($request->isMethod('get')) {
        if ($request->isAction('/api/names')) {
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
        echo 'unknown action ' . $request->getUri();
        exit();
    }

    if ($request->isMethod('post')) {
        if ($request->isAction('/api/names')) {
            $stmt = $db->prepare('insert into names values (null, :name, :first, :second, :third, :fourth, :fifth)');
            $stmt->bindValue(':name', $request->getData()->name, SQLITE3_TEXT);
            $stmt->bindValue(':first', $request->getData()->first, SQLITE3_TEXT);
            $stmt->bindValue(':second', $request->getData()->second, SQLITE3_TEXT);
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
        echo 'unknown action ' . $request->getUri();
        exit();
    }

    if ($request->isMethod('put')) {
        if ($request->isAction('/api/names/:id')) {
            $stmt = $db->prepare('update names set name = :name where id = :id');
            $stmt->bindValue(':name', $request->getData()->name, SQLITE3_TEXT);
            $stmt->bindValue(':id', $request->getId(), SQLITE3_INTEGER);

            if (!$stmt->execute()) {
                http_response_code(400);
                echo 'an error uccured while updating your data, make sure it is formatted correctly';
                exit();
            }

            http_response_code(204);
            exit();
        }

        http_response_code(501);
        echo 'unknown action ' . $request->getUri();
        exit();
    }

    if ($request->isMethod('delete')) {
        if ($request->isAction('/api/names')) {
            $query = 'delete from names';
            $result = $db->exec($query);
            http_response_code(204);
            exit();
        }

        if ($request->isAction('/api/names/:id')) {
            $stmt = $db->prepare('delete from names where id = :id');
            $stmt->bindValue(':id', $request->getId(), SQLITE3_INTEGER);

            if (!$stmt->execute()) {
                http_response_code(400);
                echo 'an error uccured while deleting your data, make sure your data is formatted correctly';
                exit();
            }

            http_response_code(204);
            exit();
        }

        http_response_code(501);
        echo 'unknown action ' . $request->getUri();
        exit();
    }

    http_response_code(501);
    echo 'unknown method ' . $request->getMethod();
    exit();
?>
