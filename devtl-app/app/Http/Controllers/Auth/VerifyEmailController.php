<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class VerifyEmailController extends Controller
{
    public function verifyEmail($token)
    {
        try {
            $token = decrypt($token);
            $token = explode('|', $token);

            $user = User::whereId($token[0])
                ->whereEmail($token[1])
                ->whereNull('email_verified_at')
                ->first();

            Auth::logout();
            if (isset($user)) {
                $user->email_verified_at = date('Y-m-d H:i:s');
                $user->save();
                Auth::login($user);

                return redirect(route('home'))->with('alert', [
                    'class' => 'success',
                    'message' => __('form.thanks_for_verifying_your_email')
                ]);
            }
        } catch (DecryptException $e) {
            Log::error('Invalid token error: '.$token);
        }

        return redirect(route('login'))->with('alert', [
            'class' => 'danger',
            'message' => __('form.invalid_token_error_message'),
        ]);
    }
}
