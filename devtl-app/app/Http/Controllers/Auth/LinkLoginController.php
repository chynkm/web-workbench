<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserToken;
use App\Notifications\LinkLoginEmail;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class LinkLoginController extends Controller
{
    public function login(Request $request)
    {
        try {
            $token = decrypt($request->token);
            $token = explode('|', $token);

            $userToken = UserToken::whereUserId($token[0])
                ->whereToken($token[1])
                ->whereNull('logged_in')
                ->first();

            // if logged-in user is different, then logout
            if (Auth::id() != $token[0]) {
                Auth::logout();
            }

            if (isset($userToken)) {
                $userToken->logged_in = date('Y-m-d H:i:s');
                $userToken->save();
                $userToken->user
                    ->logInAfterlogOutOtherSessions();

                return redirect(route('home'))->with('alert', [
                    'class' => 'success',
                    'message' => __('form.logged_in_successfully')
                ]);
            }
        } catch (DecryptException $e) {
            Log::error('Invalid token error: '.$request->token);
        }

        return redirect(route('login'))->with('alert', [
            'class' => 'danger',
            'message' => __('form.invalid_token_error_message'),
        ]);
    }

    public function sendLinkLoginEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|string',
        ]);

        $user = User::whereEmail($request->email)
            ->first();

        if ($user) {
            $user->notify(new LinkLoginEmail($user));
            $alert = [
                'class' => 'success',
                'message' => __('form.login_email_sent'),
            ];
        } else {
            $alert = [
                'class' => 'danger',
                'message' => __('form.user_not_found'),
            ];
        }

        return redirect()->route('login')
            ->with('alert', $alert);
    }
}
