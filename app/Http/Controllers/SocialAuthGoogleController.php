<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\employee_data;

use Socialite;
use Auth;

class SocialAuthGoogleController extends Controller
{
    public function redirect()
    {

        return Socialite::driver('google')->redirect();

    }

   public function showNoAccess(){
       return 'No Access';
   }


    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->stateless()->user();
            $existUser = User::where('email',$googleUser->email)->first();

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
                $newUser = employee_data::where('email',$googleUser->email)->first();

                if($newUser!=null){
                    $user = new User;
                    $user->employeeID = $newUser->employeeID;
                    $user->email = $googleUser->email;
                    $user->rightID = "1";
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
               
                return redirect()->route('/');


            }
            return redirect()->to('home');
            // return $existUser;

        } 
        catch (Exception $e) {
            return $e;
        }


    }
}
