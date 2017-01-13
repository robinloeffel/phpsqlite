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
        // todo: adapt to rest
        //
        // $query = 'insert into names values (null, "michaela", "äuäää", "glöibig", "chli hingedrii", "no hübsch", "glöibig")';
        // $db->query($query);
        //
        // $query = 'select name from names order by id desc limit 1';
        // $result = $db->query($query);
        //
        // echo $result->fetchArray(SQLITE3_ASSOC)['name'] . ' hinzugefügt';

        echo 'post';
    }

    if ($reqMethod == 'PUT') {
        echo 'put';
    }

    if ($reqMethod == 'DELETE') {
        echo 'delete';
    }
?>
