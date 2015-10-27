<?php
class Param
{
    public static function get($name, $default = null)
    {
        return isset($_REQUEST[$name]) ? trim($_REQUEST[$name]) : $default;
    }

    public static function params()
    {
        return $_REQUEST;
    }

    public static function getAll()
    {
        $params = $_REQUEST;
        if (isset($params['dc_action'])) {
            unset($params['dc_action']);
        }
        return $params;
    }
}
