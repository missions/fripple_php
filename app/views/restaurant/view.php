<?php
$advertisements = array();
foreach ($active_advertisements as $active_advertisement) {
    $advertisements[] = get_advertisement_response($active_advertisement);
}

$following_users = array();
foreach ($followers as $follower) {
    $following_users[] = get_user_response($follower);
}

$response = array(
    'isError'           => false,
    'is_success'        => true,
    'module_name'       => $module_name,
    'restaurant'        => get_restaurant_response($restaurant),
    'advertisements'    => $advertisements,
    'followers'         => $following_users,
    'user'              => get_user_response($user),
    'error_message'     => ''
);