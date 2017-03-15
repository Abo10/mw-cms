<?php


class CUserMessage
{
    public static function setFlash($key, $message)
    {
        $_SESSION['user_flashes'][$key] = $message;
    }

    public static function hasFlash($key)
    {
        if (isset($_SESSION['user_flashes'][$key]))
            return true;
        return false;
    }

    public static function getFlash($key)
    {
        $m = $_SESSION['user_flashes'][$key];
        unset($_SESSION['user_flashes'][$key]);
        return $m;
    }

}