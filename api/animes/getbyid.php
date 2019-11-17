<?php

header("Content-type: Application/json");

$data = json_decode(file_get_contents('php://input'), true);
$response = [];

$anime = R::load('animes', $data['anime_id'])->export();

function remove_bbcode(string $value): string
{
    return preg_replace('/[[^]]+]/g', '', $value);
}

switch ($anime['type']) {
    case "tv":
        $anime['type'] = "Сериал";
        break;
    case "movie":
        $anime['type'] = "Полнометражный фильм";
        break;
    case "special":
        $anime['type'] = "Спецвыпуск";
        break;
}
switch ($anime['status']) {
    case "released":
        $anime['status'] = "Вышел";
        break;
    case "ongoing":
        $anime['status'] = "Онгоинг";
        break;
    case "anons":
        $anime['status'] = "Анонс";
        break;
}

$genres = json_decode($anime['genres'], true);
$studios = json_decode($anime['studios'], true);


$tmpGenresArray = [];
foreach ($genres as $genre) {
    $tmpGenresArray[] = $genre['russian'];
}
$anime['genres'] = implode(', ', $tmpGenresArray);

$tmpStudiosArray = [];
foreach ($studios as $studio) {
    $tmpStudiosArray[] = $studio['name'];
}
$anime['studios'] = implode(', ', $tmpStudiosArray);

$tmpYear = explode('-', $anime['aired_on']);
$anime['aired_on'] = $tmpYear[0];

//$anime['description'] = remove_bbcode($anime['description']);


$response = $anime;

echo json_encode($response);