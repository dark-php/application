<?php

namespace DarkTec\Helpers;

class Auth
{

    private static $user = false;

    public static function login($username, $password)
    {

        $query = DB::select()->from('users')->cols(['*'])->where('username = :username', ['username' => $username]);
        $user = DB::execute($query);

        if (!$user) return false;

        if (password_verify($password, $user['password'])) {
            $_SESSION['auth'] = true;
            unset($user['password']);
            self::$user = $user;
            
            return true;
        }
        return false;
    }

    public static function logout()
    {
        $_SESSION['auth'] = false;
    }

    public static function isLoggedIn()
    {
        if (array_key_exists('auth', $_SESSION)) {
            return $_SESSION['auth'];
        }

        return false;
    }
}
