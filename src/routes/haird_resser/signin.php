<?php

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// haird_resser sign in
$app->post('/api/haird_resser/signin', function (Request $request, Response $response) {
    $hdEmail = $request->getParam('hd_email');
    $hdPassword = md5($request->getParam('hd_password'));


    try {
        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();

        $get_hd_query = $db->prepare(
            "select * 
                      from Hairdresser 
                      where hdEmail=:mail and hdPassword=:password");
        $get_hd_query->execute(array(
            'mail' => $hdEmail,
            'password' => $hdPassword
        ));

        //dönen satır sayısını belirtir
        $row_count = $get_hd_query->rowCount();

        if ($row_count == 1) {

            $hairdresser = $get_hd_query->fetch(PDO::FETCH_OBJ);

            $data = array(
                'status' => 'ok',
                'data' => $hairdresser,
                'message' => 'haird_resser is signed in'
            );
            return $response->withJson($data);
        }
        else {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'password or email is incorrect'
            );
            return $response->withJson($data);
        }
    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }

});