<?php
$active_advertisements = array();
foreach ($advertisements as $advertisement) {
    $active_advertisements[] = get_advertisement_response($advertisement);
}
$response = array(
    'isError'           => false,
    'is_success'        => true,
    'module_name'       => $module_name,
    'advertisements'    => $active_advertisements,
    'user'              => get_user_response($user),
    'error_message'     => ''
);