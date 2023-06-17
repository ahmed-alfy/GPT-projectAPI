<?php

namespace App\Traits;


use App\Models\Chat;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Auth;

trait ChatFunctionTrait {


use GeneralTrait;
//////////////////////////////////////////
///////// searchInJsonFile ///////////////
//////////////////////////////////////////

    public function searchInJsonFile($userMessage){


    $jsonFile = file_get_contents(base_path('assets/json/file.json'));
    if(!empty($jsonFile)){
        $json = json_decode($jsonFile,true);
        foreach ($json as $record) {

            if($record["messages"] == $userMessage ){

            return $this->returnData(200,'file ',$record["reply"],$userMessage);

            }
        }
    }
    return null;
}

//////////////////////////////////////////
///////// getChatCompletionFromAPI ///////
//////////////////////////////////////////

    public function getChatCompletionFromAPI($userMessage){
        $client = new Client();
        $response = $client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
            ],
            'json' => [
                "model" => "gpt-3.5-turbo",
                'messages' => [
                    [
                        "role" => "user",
                        "content" => $userMessage
                    ]
                ],
                'temperature' => 0.7,   #between 0 - 2
                'max_tokens' => 60,
                'n' => 1,
                'stop' => ['.'],
            ],
        ]);
        $result = json_decode($response->getBody()->getContents(), true);

        return $data = $result['choices'][0]['message']['content'];

    }

//////////////////////////////////////////
///////// saveChatRecord /////////////////
//////////////////////////////////////////

    public function saveChatRecord($userMessage,$data){
        $jsonFile = file_get_contents(base_path('assets/json/file.json'));
        Chat::create([
            'user_msg'=>$userMessage,
            'reply'=> $data ,
            'user_id'=>Auth::guard('api')->id(),
        ]);

        $newData[] =[
            'messages'=>$userMessage,
            'reply'=>$data,
        ];

        if(!empty($jsonFile)){
            $jdata = json_decode($jsonFile, true);
            $jdata = array_merge($jdata, $newData);
        }else{
            $jdata = $newData ;
        }
        $json = json_encode($jdata,JSON_PRETTY_PRINT);
        file_put_contents(base_path('assets/json/file.json'), $json);

    }
}
