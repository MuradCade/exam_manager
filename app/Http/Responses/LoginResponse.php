<?php

namespace App\Http\Responses;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;


class LoginResponse implements LoginResponseContract
{
    public function toResponse($request)
    {
        $user = Auth::user();

        return match ($user->role) {
            'owner'   => redirect()->route('owner.dashboard'),
            'editor'   => redirect()->route('editor.dashboard'),
            'readonly'   => redirect()->route('readonly.dashboard'),
            default   => redirect()->route('login'),
        };
    }
}
