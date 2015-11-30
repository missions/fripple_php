<?php
$response = array(
    'isError'           => false,
    'is_success'        => true,
    'module_name'       => $module_name,
    'restaurant'        => get_restaurant_response($restaurant),
    'user'              => get_user_response($user),
    'error_message'     => ''
);