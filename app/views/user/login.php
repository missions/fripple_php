<?php
$response = array(
    'is_success'    => true,
    'module_name'   => 'login',
    'session_info' => convert_session($session_info),
    'error_message' => ''
);