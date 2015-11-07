<?php
class Time
{
    public static function now($time_from_now = null)
    {
        if ($time_from_now) {
            return date('Y-m-d H:i:s', strtotime($time_from_now));            
        }
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

    public static function beforeEq($datetime)
    {
        return strtotime($datetime) <= self::unix();
    }

    public static function after($datetime)
    {
        return strtotime($datetime) > self::unix();    
    }

    public static function afterEq($datetime)
    {
        return strtotime($datetime) >= self::unix();    
    }
}