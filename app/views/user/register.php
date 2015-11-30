<?php
$response = array(
    'is_error'      => false,
    'is_success'    => true,
    'module_name'   => $module_name,
    'user'          => get_user_response($user),
    'error_message' => 'You have successfully created your account'
);