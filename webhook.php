<?php

require_once __DIR__ . '/vendor/autoload.php';
if (array_key_exists("TOKEN", $_ENV)){
    $token = $_ENV["TOKEN"];
    file_put_contents("log", "environment var exists" . PHP_EOL, FILE_APPEND | LOCK_EX);
} else {
    $token = file_get_contents("token");
    file_put_contents("log", "environment var does not exist" . PHP_EOL, FILE_APPEND | LOCK_EX);
}
$api_query_maker = new \App\Models\ApiQueryMaker($token);
file_put_contents("log", "api q maker obj created" . PHP_EOL, FILE_APPEND | LOCK_EX);

$data = file_get_contents("php://input");
file_put_contents("index.html", $data . PHP_EOL, FILE_APPEND | LOCK_EX);


$message = json_decode($data, true);

file_put_contents("log", $data . PHP_EOL, FILE_APPEND | LOCK_EX);

$msgtxt = $message["message"]["text"];
$chat_id = $message["message"]["chat"]["id"];
if ($msgtxt === "/start") {
    $response = $api_query_maker->send_message($chat_id , "Привет!\nЯ - бот, решающий квадратные уравнения. Для решения, введи коэффициенты через пробел.\n Например так: 1 4 3");
    file_put_contents("log", "Привет!\nЯ - бот, решающий квадратные уравнения. Для решения, введи коэффициенты через пробел.\n Например так: 1 4 3" . PHP_EOL, FILE_APPEND | LOCK_EX);
} else {

    $check_input_res = \App\Controllers\QuadraticController::check_input($msgtxt);
    if ($check_input_res === true) {
        $coef = \App\Controllers\QuadraticController::get_coefs($msgtxt);
        $in_real_numbers = \App\Controllers\QuadraticController::in_real_numbers($coef);
        if ($in_real_numbers){
            $roots = \App\Controllers\QuadraticController::solve($coef);
            $response_text = \App\Controllers\QuadraticController::respond($roots);
        } else {
            $response_text = "Дискриминант отрицательный. Решение в комплексных числах.\nА я их не знаю ;(";
            file_put_contents("log", "Дискриминант отрицательный. Решение в комплексных числах.\nА я их не знаю ;(" . PHP_EOL, FILE_APPEND | LOCK_EX);
        }
        $response = $api_query_maker->send_message($chat_id , $response_text);
    
    } else {
        $response = $api_query_maker->send_message($chat_id , "Неверный ввод.\nПопробуй ещё раз по шаблону: 1 4 3");
        file_put_contents("log", "Неверный ввод.\nПопробуй ещё раз по шаблону: 1 4 3" . PHP_EOL, FILE_APPEND | LOCK_EX);
    }
}





