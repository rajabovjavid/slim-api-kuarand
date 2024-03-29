<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->delete('/api/hdContact/deleteHdContact', function (Request $request, Response $response) {

    $hdContactId= $request->getParam('hd_contact_id');

    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        // delete hd contact
        $delete_hdContact_query = $db->prepare("CALL deleteHdContact(?)");
        $delete_hdContact_query->bindParam(1, $hdContactId, PDO::PARAM_INT);
        $delete = $delete_hdContact_query->execute();


        if (!$delete) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'hd contact is not deleted'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'hd contact is deleted'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});