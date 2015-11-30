<?php
$restaurant_response = array();
foreach ($restaurants as $restaurant) {
    $restaurant_response = get_restaurant_response($restaurant);
}
$response = array(
    'isError'       => false,
    'is_success'    => true,
    'module_name'   => $module_name,
    'restaurants'   => $restaurant_response,
    'user'          => get_user_response($user),
    'error_message' => ''
);