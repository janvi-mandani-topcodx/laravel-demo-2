<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Mail\EmailVerificationMail;
use App\Mail\ResetPasswordMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{

    public function login(LoginRequest $request)
    {
        $input = $request->all();
        $user = User::where('email' , $input['email'])->first();

        if($user){
            if(Hash::check($input['password'] , $user->password)){
                Auth::login($user);
                return redirect()->route('user.index');
            }
        }
        else{

            return redirect()->route('login.user');
        }
    }

    public function logout(){
        Auth::logout();
        return redirect()->route('login.user');
    }

    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $user = User::where('email' , $request->email)->first();
        if($user){
            $message = 'Hello';
            $subject = 'Reset Password';
            $email = $request->email;
            Mail::to($user)->send(new ResetPasswordMail($message, $subject , $email));
            return redirect()->route('forgot.password');
        }
        else{
            return redirect()->route('forgot.password');
        }
    }

    public function resetPassword(ResetPasswordRequest $request){
        $input = $request->all();
        $user = User::where('email' , $input['email'])->first();
        if($user){
            if(Hash::check($input['old_password'] , $user->password)){
                if($input['new_password'] == $input['confirm_password']){
                    $user->password = Hash::make($input['new_password']);
                    $user->save();
                    Auth::login($user);
                    return redirect()->route('user.index');
                }
            }
        }
    }

    public function verify($id)
    {
        $user = User::findOrFail($id);

        if (!$user->email_verified_at) {
            $user->email_verified_at = now();
            $user->save();
        }
        return redirect()->route('user.index');
    }

    public function emailVerify()
    {
        $user = auth()->user();
        $message = 'Hello';
        $subject = 'Email Verification';
        Mail::to($user->email)->send(new EmailVerificationMail($message, $subject, $user));
        return view('auth.email-verification');
    }
}
