<?php

namespace App\Http\Middleware;

use App\Mail\EmailVerificationMail;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\Response;

class EmailVerificationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();
        if ($user && !$user->email_verified_at){
            $message = 'Hello';
            $subject = 'Email Verification';
            Mail::to($user->email)->send(new EmailVerificationMail($message, $subject, $user));
            return redirect()->route('email.verification');
        }
        return $next($request);

    }
}
