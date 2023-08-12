<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

/**
 * @OA\Post(
 *     path="/login",
 *     summary="Login the user",
 *     tags={"Auth"},
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="email",
 *                     type="string"
 *                 ),
 *                 @OA\Property(
 *                     property="password",
 *                     type="string"
 *                 ),
 *                 example={"email": "admin@chorkleines.com", "password": "password"}
 *             )
 *         )
 *     ),
 *     @OA\Response(
 *         response=204,
 *         description="Success",
 *     ),
 *     @OA\Response(
 *         response=422,
 *         description="Failed",
 *         @OA\JsonContent(
 *             allOf={
 *                 @OA\Schema(
 *                     @OA\Property(
 *                         property="message", 
 *                         type="string", 
 *                         example="These credentials do not match our records.",
 *                     ),
 *                     @OA\Property(
 *                         property="errors", 
 *                         type="object", 
 *                         @OA\Property(
 *                             property="email", 
 *                             type="array", 
 * 	   	                       @OA\Items(
 * 	                               type="string",
 * 	                               example="These credentials do not match our records.",
 * 	                           ),
 *                         ),
 *                     ),
 *                 ),
 *             }
 *         ),
 *     ),
 * )
 *
 * @OA\Post(
 *     path="/logout",
 *     summary="Logout the user",
 *     tags={"Auth"},
 *     @OA\Response(
 *         response=204,
 *         description="Success",
 *     ),
 * )
 */
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
