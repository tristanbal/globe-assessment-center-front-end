<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;

use Illuminate\Contracts\Auth\Authenticatable;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Auth;
use App\User;
use App\employee_data;
use Exception;


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
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function authenticated($request , $user){
        if (Auth::user() && Auth::user()->rightID == 1) {
            return redirect('/home');
        }else{
            Auth::logout();
            return 'no access';
        }
    }

    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleProviderCallback()
    {
        //$user = Socialite::driver('google')->user();

        //$user->token(); 
        //$user->name();

        // $user->token;

        
        // gawa ni glenn

        try {
            
            $googleUser = Socialite::driver('google')->stateless()->user();
            //return $googleUser->email;
            $existUser = User::where('email',$googleUser->email)->first();

            //return $existUser;
            if($existUser) {
                // Auth::loginUsingId($existUser->id);
                // Auth::login($existUser, true);
                // return $existUser;
                // auth()->login($existUser, true);
                // Auth::login($existUser);
                // auth()->login($existUser, true);
                
                Auth::login($existUser);

            }
            else {
                //return 'test';
                $newUser = employee_data::where('email',$googleUser->email)->first();
                $image = $googleUser->avatar;


                if($newUser!=null){
                    $user = new User;
                    $user->employeeID = $newUser->id;
                    $user->email = $googleUser->email;
                    $user->rightID = "1";
                    $user->profileImage = $image;
                    // $user->password = md5(rand(1,10000));
                    $user->password = md5(microtime());

                    $user->save();
                    Auth::loginUsingId($user->id);
                    return redirect()->to('home');

                }
               
                // $user = new User;
                // $user->name = $googleUser->name;
                // $user->email = $googleUser->email;
                // $user->google_id = $googleUser->id;
                // $user->password = md5(rand(1,10000));
                // $user->save();
                // Auth::loginUsingId($user->id);
                return "Your email don't have access to this portal.";
                return redirect()->route('noAccess');


            }
            return redirect()->to('home');
            // return $existUser;

        } 
        catch (Exception $e) {
            return $e;
        }
    }
}
