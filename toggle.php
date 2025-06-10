<?php

header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: Content-Type");
header("Content-Type: application/json");


if (
    $_SERVER["REQUEST_METHOD"] === "POST"
) {

    $data = json_decode(file_get_contents("php://input"), true);
    $json_file = file_get_contents("tasks.json");
    $tasks = json_decode($json_file, true);

    $id = $data["id"];
    $status = $data["status"] === "completed" ? true : false;


    foreach ($tasks as &$task) {
        if ($task["id"] == $id) {
            $task["done"] = $status;
            break;
        }
    }
    unset($task); //// h logikh ths antikatastashs tou Done status auth h  for !

    $tasks = json_encode($tasks, JSON_PRETTY_PRINT);

    if (file_put_contents("tasks.json", $tasks)) {
        echo json_encode(["status" => $status]); /////stelnw to status pisw sthn js gia na 3erw pws 8a xeiristw thn classList
        exit;
    } else {
        echo json_encode(["status" => "failed"]);
        exit;
    }
}
