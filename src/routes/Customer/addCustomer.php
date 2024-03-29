<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


// customer sign up
$app->post('/api/customer/addCustomer', function (Request $request, Response $response) {
    $cusName = $request->getParam('cus_name');
    $cusEmail = $request->getParam('cus_email');
    $cusPassword = md5($request->getParam('cus_password'));
    $cusPhone = $request->getParam('cus_phone');
    try {

        // Get DB Object
        $db = new db();
        // Connect
        $db = $db->connect();


        // checking whether email used or not
        $get_customer_query = $db->prepare("select * from Customer where customerEmail=:mail");
        $get_customer_query->execute(array(
            'mail' => $cusEmail
        ));

        $row_count = $get_customer_query->rowCount();

        if ($row_count != 0) {
            $data = array(
                'status' => 'error',
                'error_code' => 1,
                'message' => 'used email'
            );
            return $response->withJson($data);
        }

        $add_customer_query = $db->prepare("CALL addCustomer(?, ?, ?, ?)");
        $add_customer_query->bindParam(1, $cusName, PDO::PARAM_STR);
        $add_customer_query->bindParam(2, $cusEmail, PDO::PARAM_STR);
        $add_customer_query->bindParam(3, $cusPassword, PDO::PARAM_STR);
        $add_customer_query->bindParam(4, $cusPhone, PDO::PARAM_STR);
        $add = $add_customer_query->execute();

        if (!$add) {
            $data = array(
                'status' => 'error',
                'error_code' => 2,
                'message' => 'customer not added'
            );
            return $response->withJson($data);
        }

        $data = array(
            'status' => 'ok',
            'message' => 'customer is added'
        );
        return $response->withJson($data);

    } catch (PDOException $e) {
        echo '{"error": {"text": ' . $e->getMessage() . '}';
    }
});
