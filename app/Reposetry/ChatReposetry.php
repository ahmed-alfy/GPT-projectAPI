<?php
namespace App\Reposetry;


use App\Models\User;
use App\Traits\GeneralTrait;
use App\Interface\ChatInterface;
use App\Traits\ChatFunctionTrait;
use Illuminate\Support\Facades\Auth;
use GuzzleHttp\Exception\ConnectException;


class ChatReposetry   implements ChatInterface{

    use GeneralTrait,ChatFunctionTrait;

    public function chat($validator){
        try {

            //get the user message
            $userMessage = strtolower($validator->messages);

            // start by search in the json file
            $jsonResponse = $this->searchInJsonFile($userMessage);

            // if it find the data return it ;
            if($jsonResponse != null){
                return $jsonResponse;
            }

            // if not find the massage in the file get it by the API

            $data = $this->getChatCompletionFromAPI($userMessage);

            //  store the data  database and in the json file
            $this->saveChatRecord($userMessage,$data);

            // return API data
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
