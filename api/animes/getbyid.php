<?php

header("Content-type: Application/json");

$data = json_decode(file_get_contents('php://input'), true);
$response = [];

$anime = R::load('animes', $data['anime_id'])->export();

$response = $anime;

echo json_encode($response);