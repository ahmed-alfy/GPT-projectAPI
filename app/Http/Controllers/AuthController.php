<?php
namespace App\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Reposetry\AuthReposetry;
use App\Traits\GeneralTrait;

class AuthController extends Controller
{
    use GeneralTrait;
    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct(protected AuthReposetry $authReposetry) {
        $this->middleware('JWT.verified:api', ['except' => ['login', 'register']]);
    }


    public function login(LoginRequest $request){

        return $this->authReposetry->login($request);
    }

    public function register(RegisterRequest $request){

        return $this->authReposetry->register($request);
    }

    public function logout(){

        return $this->authReposetry->logout();
    }

    public function refresh() {

        return $this->authReposetry->refresh();
    }

    public function userProfile() {

        return $this->authReposetry->userProfile();
    }

    // /**
    //  * Get a JWT via given credentials.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function login(Request $request){
    // 	$validator = Validator::make($request->all(), [
    //         'email' => 'required|email',
    //         'password' => 'required|string',
    //     ]);
    //     if ($validator->fails()) {
    //         $code = $this->returnCodeAccordingToInput($validator);
    //         return $this->returnValidationError('422',$code,$validator);
    //     }
    //         if (! $token = Auth::guard('api')->attempt($validator->validated())) {
    //             return $this->returnError('401','','Unauthorized');
    //     }
    //     return $this->createNewToken($token);
    // }
    // /**
    //  * Register a User.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function register(Request $request) {
    //     $validator = Validator::make($request->all(), [
    //         'name' => 'required|string|between:2,100',
    //         'email' => 'required|string|email|max:100|unique:users',
    //         'password' => 'required|string',
    //     ]);
    //     if($validator->fails()){

    //         $code = $this->returnCodeAccordingToInput($validator);
    //         return $this->returnValidationError('400',$code,$validator);
    //     }
    //     $user = User::create(array_merge(
    //                 $validator->validated(),
    //                 ['password' => bcrypt($request->password)]
    //             ));

    //         return $this->returnSuccessMessage(200,'User successfully registered');
    // }

    // /**
    //  * Log the user out (Invalidate the token).
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function logout() {
    //     Auth::guard('api')->logout();
    //     return $this->returnSuccessMessage(200,'User successfully signed out');

    // }
    // /**
    //  * Refresh a token.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function refresh() {
    //     return $this->createNewToken(Auth::guard('api')->refresh());
    // }
    // /**
    //  * Get the authenticated User.
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // public function userProfile() {

    //     $user = Auth::guard('api')->user();
    //     return $this->returnData(200,'user',$user);
    //     // return response()->json(Auth::guard('api')->user());
    // }
    // /**
    //  * Get the token array structure.
    //  *
    //  * @param  string $token
    //  *
    //  * @return \Illuminate\Http\JsonResponse
    //  */
    // protected function createNewToken($token){
    //     return response()->json([
    //         'access_token' => $token,
    //         'token_type' => 'bearer',
    //         'expires_in' => Auth::guard('api')->factory()->getTTL() * 60 .' s',
    //         'user' => Auth::guard('api')->user()
    //     ]);
    // }
}
