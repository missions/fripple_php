<?php
$response = array(
    'isError'           => false,
    'is_success'        => true,
    'module_name'       => $module_name,
    'advertisement'     => get_advertisement_response($advertisement),
    'user'              => get_user_response($user),
    'error_message'     => ''
);