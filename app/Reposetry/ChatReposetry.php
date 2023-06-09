<?php
namespace App\Reposetry;

use App\Models\Chat;
use App\Models\User;
use GuzzleHttp\Client;
use App\Traits\GeneralTrait;
use App\Interface\ChatInterface;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\ConnectException;


class ChatReposetry implements ChatInterface{

    use GeneralTrait;
    public function chat($validator){
        try {

            $userMessage = strtolower($validator->messages);

            $jsonFile = file_get_contents(base_path('assets/json/file.json'));
            if(!empty($jsonFile)){
            $json = json_decode($jsonFile,true);
            foreach ($json as $record) {

                if($record["messages"] == $userMessage ){
                return $this->returnData(200,'file ',$record["reply"],$userMessage);
                // return $record["reply"];
                }

            }
            }

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
                $data = $result['choices'][0]['message']['content'];

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

                return $this->returnData(200,'chat GPT ',$data,$userMessage);

        }catch (ConnectException $e) {
            $handlerContext = $e->getHandlerContext();
            if ($handlerContext['errno'] ?? 0) {

                $errno = (int)($handlerContext['errno']);
            }
            $errorMessage = $handlerContext['error'] ?? $e->getMessage();
            return $this->returnError('404','E00'.$errno ,$errorMessage);

        }catch (\Exception $e) {

            $message = $e->getMessage();
            return $this->returnError('404','E404',$message);
        }
    }

    public function history(){
        try{
            $history = User::with('chat')->where('id',Auth::guard('api')->id())->get();
            // return $this->returnError(200,'S0001',$history);
            return $this->returnData(200,'history',$history);

            // return $history;
        }catch(\Exception $e){
            return $this->returnError(500,'E002',$e->getMessage());
        }
    }
}
