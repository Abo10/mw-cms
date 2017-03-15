<?php

class CMessage
{
    public static function setFlash($key, $message)
    {
        $_SESSION['flashes'][$key] = $message;
    }

    public static function hasFlash($key)
    {
        if (isset($_SESSION['flashes'][$key]))
            return true;
        return false;
    }

    public static function getFlash($key)
    {
        $m = $_SESSION['flashes'][$key];
        unset($_SESSION['flashes'][$key]);
        return $m;
    }
}

?>