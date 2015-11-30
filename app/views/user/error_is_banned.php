<?php
$response = array(
    'is_error'      => true,
    'is_success'    => false,
    'module_name'   => $module_name,
    'error_message' => sprintf('User [%s] is banned and cannot login.', $user->getId())
);