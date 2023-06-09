<?php

namespace App\Http\Controllers;


use App\Traits\GeneralTrait;
use App\Http\Requests\MessageRequest;
use App\Interface\ChatInterface;



class ChatController extends Controller
{

    use GeneralTrait;

    public function __construct(protected ChatInterface $chatInterface){
        $this->middleware('JWT.verified:api');
    }

    public function chat(MessageRequest $request){
        return $this->chatInterface->chat($request);
    }


    public function history(){
        return $this->chatInterface->history();
    }
}
