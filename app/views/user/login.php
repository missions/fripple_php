<?php
$response = array(
    'isError'       => false,
    'is_success'    => true,
    'module_name'   => $module_name,
    'session_info'  => get_session_response($session),
    'user'          => get_user_response($user),
    'error_message' => ''
);