<?php
class Time
{
    public static function now()
    {
        return date('Y-m-d H:i:s');
    }

    public static function unix()
    {
        return time();
    }

    public static function before($datetime)
    {
        return strtotime($datetime) < self::unix();
    }

    public static function before_eq($datetime)
    {
        return strtotime($datetime) <= self::unix();
    }

    public static function after($datetime)
    {
        return strtotime($datetime) > self::unix();    
    }

    public static function after_eq($datetime)
    {
        return strtotime($datetime) >= self::unix();    
    }
}