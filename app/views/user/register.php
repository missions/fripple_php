<?php
$response = array(
    'is_error'      => false,
    'is_success'    => true,
    'module_name'   => 'registration',
    'user'          => get_user_response($user),
    'error_message' => 'You have successfully created your account'
);