<?php
    require_once 'Request.php';
    require_once 'Database.php';
    require_once 'Response.php';

    $request = new Request($_SERVER);
    $database = new Database('names.db');

    if ($request->isMethod('get')) {
        if ($request->isAction('/api/names')) {
            $headers = [
                'Content-Type' => 'application/json; charset=utf-8'
            ];
            $data = $database->getNames();

            Response::send(200, $headers, $data);
        }

        Response::send(501, [], 'unknown action: ' . $request->getUri());
    }

    if ($request->isMethod('post')) {
        if ($request->isAction('/api/names')) {
            $database->insertName(
                $request->getData()->name,
                $request->getData()->first,
                $request->getData()->second,
                '',
                '',
                ''
            );

            $headers = [
                'Content-Type' => 'application/json; charset=utf-8'
            ];
            $data = $database->getLastInsertedRow();

            Response::send(201, $headers, $data);
        }

        Response::send(501, [], 'unknown action: ' . $request->getUri());
    }

    if ($request->isMethod('put')) {
        if ($request->isAction('/api/names/:id')) {
            $database->updateNameById(
                $request->getId(),
                $request->getData()->name
            );

            Response::send(204, [], '');
        }

        Response::send(501, [], 'unknown action: ' . $request->getUri());
    }

    if ($request->isMethod('delete')) {
        if ($request->isAction('/api/names')) {
            $database->deleteNames();

            Response::send(204, [], '');
        }

        if ($request->isAction('/api/names/:id')) {
            $database->deleteNameById($request->getId());

            Response::send(204, [], '');
        }

        Response::send(501, [], 'unknown action: ' . $request->getUri());
    }

    Response::send(501, [], 'unknown action: ' . $request->getMethod());
?>
