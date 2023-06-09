<?php
namespace App\Reposetry;

use App\Models\User;
use App\Traits\GeneralTrait;
use App\Interface\AuthInterface;
use Illuminate\Support\Facades\Auth;

class AuthReposetry implements AuthInterface{

    use GeneralTrait;

    public function login($validator){
        try{

            if (! $token = Auth::guard('api')->attempt($validator->validated())) {
                return $this->returnError('401','','Unauthorized');
            }else{
                return $this->createNewToken($token);
            }
        }catch(\Exception $e){
            return $this->returnError('500','',$e->getMessage());
        }
    }

    public function register($validator) {
        try{
            $user = User::create(array_merge(
                $validator->validated(),
                ['password' => bcrypt($validator->password)]
            ));
            if($user)
                return $this->returnSuccessMessage(200,'User successfully registered');
            else
                return $this->returnError('500','','somsing was wrong');

        }catch(\Exception $e){
            return $this->returnError('500','',$e->getMessage());
        }
    }

    public function logout(){
        try{
            Auth::guard('api')->logout();
            return $this->returnSuccessMessage(200,'User successfully signed out');

        }catch(\Exception $e){
            return $this->returnError('500','',$e->getMessage());
        }
    }

    public function refresh() {
        try{
            return $this->createNewToken(Auth::guard('api')->refresh());
        }catch(\Exception $e){
            return $this->returnError('500','',$e->getMessage());
        }
    }

    public function userProfile() {
        try{
            $user = Auth::guard('api')->user();
            return $this->returnData(200,'user',$user);
        }catch(\Exception $e){
            return $this->returnError('500','',$e->getMessage());
        }
    }



    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60 .' s',
            'user' => Auth::guard('api')->user()
        ]);
    }
}
