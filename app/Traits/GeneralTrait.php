<?php

namespace App\Traits;



trait GeneralTrait{

    public function returnError($status,$errNum,$msg){
        return response()->json([
            'Status' => $status,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    public function returnSuccessMessage($status,$msg,$errNum = "S000"){
        return response()->json([
            'Status' => $status,
            'errNum' => $errNum,
            'msg' => $msg
        ]);
    }

    public function returnData($status ,$key, $value, $msg = "")
    {
        return response()->json([
            'Status' => $status,
            'errNum' => "S000",
            'msg' => $msg,
            $key => $value
        ]);
    }


    public function returnValidationError($status,$code = "E001", $validator)
    {
        return $this->returnError($status,$code, $validator->errors()->first());
    }

    public function returnCodeAccordingToInput($validator)
        {
            $inputs = array_keys($validator->errors()->toArray());
            $code = $this->getErrorCode($inputs[0]);
            return $code;
        }


    public function getErrorCode($input){
        if ($input == "messages")
            return 'E0011';
        else
            return 'E000011';
    }


}
