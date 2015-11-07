<?php
$response = array(
    'isError'       => false,
    'is_success'    => true,
    'module_name'   => 'login',
    'session_info'  => convert_session($session_info),
    'user'          => get_user_response($user),
    'error_message' => ''
);