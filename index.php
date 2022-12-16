<?php

require_once 'functions.php';

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, DEL');
header('Content-Type: application/json; charset=utf-8');


try {
    if (empty($_GET['page'])) {
        http_response_code(404);
        exit('Not found');
    }

//    echo '<pre>';
//    var_dump($_GET);
//    die;

    $route = $_GET['page'];
//    $data;

    if ($_GET['method'] == 'GET') {
        $data = getData($route, $_GET);
    }

    if ($_GET['method'] == 'POST') {
        $data = postData($route, $_GET);
    }

    if ($_GET['method'] == 'DELETE') {
        $data = deleteData($route, $_GET);
    }
//    switch($_GET['method']) {
//        case 'GET': {
//            $data = getData($route, $_GET);
//            break;
//        }
//
//        case 'POST': {
//            $data = postData($route, $_GET);
//            break;
//        }
//
//        case 'DEL':
//        case 'DELETE': {
//            $data = deleteData($route, $_GET);
//            break;
//        }
//
////        case 'OPTIONS': {
////            $data = ['auth' => true];
////        }
//    }

    if (empty($data)) {
        http_response_code(404);
        exit('Not found');
    }

    echo json_encode($data, JSON_UNESCAPED_UNICODE);

} catch (Exception $error) {
    http_response_code(500);
    exit('Internal server error');
}
