<?php

namespace Core;

use App\Models\User;
use Core\Session\Session;

class Auth
{
    public static function attempt(string $email, string $password)
    {
        $user = User::where('email', $email)
            ->first();
        
        if ($user && (Hash::check($password, $user->password))) {
            return static::login($user);
        }
        
        return false;
    }

    protected static function login(User $user)
    {
        Session::set('_user_id', $user->id);
        return $user;
    }

    public static function user()
    {
        return Application::$authUser;
    }

    public static function check()
    {
        if(static::user()) {
            return true;
        }

        return false;
    }

    public static function logout()
    {
        Session::remove('_user_id');
    }
}