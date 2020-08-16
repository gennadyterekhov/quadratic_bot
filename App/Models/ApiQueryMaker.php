<?php
namespace App\Models;


class ApiQueryMaker
{
    public static $token;


    public function __construct($token){
        self::$token = $token;
        return $this;
    }


    public static function make_api_query($method_name, $data=[], $method="GET"){
        $full_url = "https://api.telegram.org/bot" . self::$token . "/" . $method_name;

        $headers = ['Content-Type:application/json'];

        return \App\Models\HttpQueryMaker::make_http_query(
            $full_url,
            $data,
            $method,
            $headers
        );
    }


	public function get_me(){
        $response = self::make_api_query("getMe");
        return $response;
    }
    

	public function set_webhook($url){
        $response = self::make_api_query("setWebhook?url=" . $url);
        return $response;
    }
    
    public function send_message($chat_id, $text){
        $data = [
            "chat_id" => $chat_id,
            "text" => $text
        ];
        $response = self::make_api_query("sendMessage", $data, "POST");
        return $response;
    }
}
