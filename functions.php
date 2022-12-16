<?php

function getData($route, $params = [])
{
    $filePath = makeRoutePath($route);

//    var_dump($filePath); die;

    if (!file_exists($filePath)) {
        http_response_code(404);
        exit('Not found');
    }

    $dataJSON = file_get_contents($filePath);
    $data = json_decode($dataJSON, true);

    if (!empty($params['id'])) {
        $id = $params['id'];
        return array_find($data, function ($element) use ($id) {
            return (int)$element['id'] === (int)$id;
        });
    }

    return $data;
}

function postData($route, $params = [])
{
    $filePath = makeRoutePath($route);

    if (!file_exists($filePath)) {
        http_response_code(404);
        exit('Not found');
    }

    $dataJSON = file_get_contents($filePath);
    $data = json_decode($dataJSON, true);
    $newData;

    switch ($route) {
        case 'news':
            {
                if (empty($params['title']) || empty($params['text']) || empty($params['author'])) {
                    http_response_code(400);
                    exit('Request error');
                }

                $newData = [
                    'title' => $params['title'],
                    'text' => $params['text'],
                    'author' => $params['author'],
                ];

                break;
            }

        case 'users':
            {
                if (empty($params['login']) || empty($params['name']) || empty($params['password'])) {
                    http_response_code(400);
                    exit('Request error');
                }

                $newData = [
                    'login' => $params['login'],
                    'name' => $params['name'],
                    'password' => $params['password'],
                ];

                break;
            }

        case 'films':
            {
                if (empty($params['name']) || empty($params['url']) || empty($params['image'])) {
                    http_response_code(400);
                    exit('Request error');
                }

                $newData = [
                    "id" => $params['id'],
                    "sort_order" => $params['sort_order'],
                    "category_id" => $params['category_id'],
                    "name" => $params['name'],
                    "url" => $params['url'],
                    "duration" => $params['duration'],
                    "production" => $params['production'],
                    "genre" => $params['genre'],
                    "year" => $params['year'],
                    "image" => $params['iamge'],
                    "image_view" => $params['image_view'],
                    "director" => $params['director'],
                    "producer" => $params['producer'],
                    "scenario" => $params['scenario'],
                    "idea" => $params['idea'],
                    "music" => $params['music'],
                    "camera" => $params['camera'],
                    "painter" => $params['painter'],
                    "trailer" => $params['trailer'],
                    "trailer2" => $params['trailer2'],
                    "film_about" => $params['film_about'],
                    "action_video" => $params['action_video'],
                    "description" => $params['description'],
                    "desc2" => $params['desc2'],
                    "desc3" => $params['desc3'],
                    "title" => $params['title'],
                    "meta_description" => $params['meta_description'],
                    "status" => $params['status']
                ];

                break;
            }

        case 'todo':
            {
                if (empty($params['label'])) {
                    http_response_code(400);
                    exit('Request POST error');
                }

                $newData = [
                    'label' => $params['label'],
                    'important' => $params['important'],
                    'done' => $params['done'],
                ];

                break;
            }
    }

    $lastDataId = $data[count($data) - 1]['id'];
    $newData['id'] = ++$lastDataId;
    $data[] = $newData;

    $dataToWriteJSON = json_encode($data, JSON_UNESCAPED_UNICODE);
    $writeReslt = file_put_contents($filePath, $dataToWriteJSON);

    if (!$writeReslt) {
        http_response_code(500);
        echo 'Internal server error';
        exit;
    }

    return $newData;
}

function deleteData($route, $params = [])
{
    $filePath = makeRoutePath($route);

    if (!file_exists($filePath)) {
        http_response_code(404);
        exit('Not found');

    } else if (empty($params['id'])) {
        http_response_code(400);
        exit('Request error');
    }

    $dataJSON = file_get_contents($filePath);
    $data = json_decode($dataJSON, true);
    $deletableId = $params['id'];

    if (!empty($data)) {
        foreach ($data as $item) {
            $dataArray[$item['id']] = $item;
        }
    }

    if (!empty($dataArray[$params['id']])) {
        $dataToDelete = $dataArray[$params['id']];
        unset($dataArray[$params['id']]);

        $dataToWriteJSON = json_encode(array_values($dataArray), true);
        $writeReslt = file_put_contents($filePath, $dataToWriteJSON);

        if (!$writeReslt) {
            http_response_code(500);
            echo 'Internal server error';
            exit;
        }

        return $dataToDelete;
    } else {
        http_response_code(404);
        exit('Not found');
    }





//    $dataToDelete = array_find($data, function ($element) use ($deletableId) {
//        return (int)$element['id'] === (int)$deletableId;
//    });
//
//    if (empty($dataToDelete)) {
//        http_response_code(404);
//        exit('Not found');
//    }
//
//    $resultData = array_filter($data, function ($element) use ($deletableId) {
//        return (int)$element['id'] !== (int)$deletableId;
//    });
//
//    $dataToWriteJSON = json_encode($resultData, true);
//    $writeReslt = file_put_contents($filePath, $dataToWriteJSON);
//
//    if (!$writeReslt) {
//        http_response_code(500);
//        echo 'Internal server error';
//        exit;
//    }
//
//    return $dataToDelete;
}

function makeRoutePath($route)
{
    $filenameJson = $route . '.json';
    $dataDirPath = __DIR__ . '/data/';
    return $dataDirPath . $filenameJson;
}

function array_find($haystack, $callback)
{
    foreach ($haystack as $element)
        if ($callback($element))
            return $element;
}
