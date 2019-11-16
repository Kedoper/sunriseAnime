<?php

$data = json_decode(file_get_contents('php://input'), true);
$response = [];
if ($data['offset'] === null) {
    $list = R::findCollection('animes');
    $count = 20;
    while ($anime = $list->next()) {
        if ($count === 0) {
            echo json_encode($response);
            die();
        }
        if ($anime['ru_name'] !== null) $anime_title = $anime['ru_name']; else $anime_title = $anime['name'];
        $response[] = [
            'title' => $anime_title,
            'photo' => "https://shikimori.one{$anime['photo']}",
            'episodes' => [
                'count' => $anime['episodes'],
                'aired' => $anime['episodes_aired']
            ],
            'type' => $anime['type'],
            'status' => $anime['status'],
        ];
        $count--;
    }
} else if (is_int($data['offset'])) {
    $list = R::findCollection('animes');
    $from = $data['offset'] + 20;
    $count = 20;
    while ($anime = $list->next()) {
        if ($from !== 0) {
            $from--;
        } else {
            if ($count === 0) {
                echo json_encode($response);
                die();
            }
            if ($anime['ru_name'] !== null) $anime_title = $anime['ru_name']; else $anime_title = $anime['name'];
            $response[] = [
                'title' => $anime_title,
                'photo' => "https://shikimori.one{$anime['photo']}",
                'episodes' => [
                    'count' => $anime['episodes'],
                    'aired' => $anime['episodes_aired']
                ],
                'type' => $anime['type'],
                'status' => $anime['status'],
            ];
            $count--;
        }
    }
}