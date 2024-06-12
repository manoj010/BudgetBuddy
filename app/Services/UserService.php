<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;

class UserService
{
    public function getUserId()
    {
        return Auth::id();
    }
}
