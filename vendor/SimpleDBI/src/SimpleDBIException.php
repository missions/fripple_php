<?php
class SimpleDBIException extends PDOException
{
    public function __construct(PDOException $super, $query_string, $params = array())
    {
        $params_array = array();
        foreach($params as $key=>$value) {
            $params_array[] = sprintf('%s=%s', $key, $value);
        }
        $params_string = implode(',', $params_array);
        $query_string = preg_replace("/(\r\n|\r|\n|\t)+/", " ", $query_string);
        $this->message = $super->getMessage() . sprintf(' [SQL: %s {%s}]', $query_string, $params_string);
        $this->code = $super->getCode();
        $this->errorInfo = $super->errorInfo;
    }
    public function isDuplicateEntry()
    {
        return ($this->errorInfo[1] == 1062);
    }
}