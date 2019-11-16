<?php

use RedBeanPHP\BeanCollection;

header("Content-type: Application/json");

$data = json_decode(file_get_contents('php://input'), true);
$response = [];

const OFFSET_ANIME_LIST = 24;

function checkData($data)
{
    if ($data['offset'] <= 0) {
        die(json_encode([
            'code' => -1,
            'message' => "Param 'offset' must be bigger than 0"
        ]));
    }
}

function getAnimeList(BeanCollection $list)
{
    $animeList = [];
    while ($anime = $list->next()) {
        if ($anime['ru_name'] !== null) $anime_title = $anime['ru_name']; else $anime_title = $anime['name'];
        $animeList[] = [
            'id' => $anime['id'],
            'title' => $anime_title,
            'photo' => "https://shikimori.one{$anime['photo']}",
            'episodes' => [
                'count' => $anime['episodes'],
                'aired' => $anime['episodes_aired']
            ],
            'type' => $anime['type'],
            'status' => $anime['status'],
        ];
    }
    return $animeList;
}

if ($data['offset'] === null) {
    $list = R::findCollection('animes', "WHERE id BETWEEN ? AND ?", [1, OFFSET_ANIME_LIST]);
    $response = getAnimeList($list);
    echo json_encode($response);

} else if (is_int($data['offset'])) {

    checkData($data);

    $offsetFrom = $data['offset'] + 1;
    $offsetTo = ($offsetFrom + OFFSET_ANIME_LIST) - 1;

    $list = R::findCollection('animes', "WHERE id BETWEEN ? AND ?", [$offsetFrom, $offsetTo]);
    $response = getAnimeList($list);
    echo json_encode($response);

} else {
    echo json_encode([
        'code' => -1,
        'message' => "Param 'offset' must be integer"
    ]);
}