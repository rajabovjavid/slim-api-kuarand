<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;


require '../vendor/autoload.php';
require '../src/config/db.php';

$app = new Slim\App;


function callAPI($method, $url, $data){
    $curl = curl_init();

    switch ($method){
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // OPTIONS:
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array(
        'APIKEY: 111111111111111111111',
        'Content-Type: application/json',
    ));
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);

    // EXECUTE:
    $result = curl_exec($curl);
    if(!$result){die("Connection Failure");}
    curl_close($curl);
    return $result;
}

// admin
require '../src/routes/Admin/addAdmin.php';
require '../src/routes/Admin/updateAdmin.php';
require '../src/routes/Admin/deleteAdmin.php';

//custo_mer routes
require '../src/routes/custo_mer/get_all.php';
require '../src/routes/custo_mer/get_name_by_email.php';
require '../src/routes/custo_mer/get_customer_by_id.php';
require '../src/routes/custo_mer/signup.php';
require '../src/routes/custo_mer/signin.php';

//haird_resser routes
require '../src/routes/haird_resser/signup.php';
require '../src/routes/haird_resser/add_address.php';
require '../src/routes/haird_resser/add_contact.php';
require '../src/routes/haird_resser/signin.php';
require '../src/routes/haird_resser/get_hds_count.php';


$app->run();