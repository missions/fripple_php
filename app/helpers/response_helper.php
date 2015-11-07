<?php
function get_user_response(User $user)
{
    $user_response = array(
        'id'            => $user->getId(),
        'username'      => $user->getUsername(),
        'email_address' => $user->getEmailAddress(),
        'first_name'    => $user->getFirstName(),
        'last_name'     => $user->getLastName(),
        'role'          => $user->getRole(),
        'is_active'     => $user->isActive(),
        'rebate_points' => $user->getRebatePoints()
    );

    if ($facebook_id = $user->getFacebookId()) {
        $user_response['facebook_id'] = $facebook_id;
    }
    return $user_response;
}

function get_session_response(AccountSession $session)
{
    return array(
        'id'            => $session->getId(),
        'user_id'       => $session->getUserId(),
        'device_id'     => $session->getDeviceId(),
        'date_start'    => $session->getDateStart(),
        'date_end'      => $session->getDateEnd(),
    );
}