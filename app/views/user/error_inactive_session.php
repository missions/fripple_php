<?php
$response = array(
    'is_error'      => true,
    'is_success'    => false,
    'module_name'   => $module_name,
    'error_message' => sprintf('User [%s]\'s session is currently inactive/expired.', $user->getId())
);