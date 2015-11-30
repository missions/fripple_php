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

function get_restaurant_response(Restaurant $restaurant)
{
    return array(
        'id'                => $restaurant->getId(),
        'name'              => $restaurant->getName(),
        'description'       => $restaurant->getDescription(),
        'contact_number'    => $restaurant->getContactNumber(),
        'latitude'          => $restaurant->getLatitude(),
        'longitude'        => $restaurant->getlongitude(),
        'num_followers'     => $restaurant->getNumFollowers(),
        'num_menu_likes'    => $restaurant->getNumMenuLikes(),
        'num_menu_dislikes' => $restaurant->getNumMenuDislikes(),
        'created'           => $restaurant->getCreated(),
        'updated'           => $restaurant->getUpdated()
    );
}

function get_advertisement_response(Advertisement $advertisement)
{
    return array(
        'id'            => $advertisement->getId(),
        'restaurant_id' => $advertisement->getRestaurantId(),
        'num_likes'     => $advertisement->getNumLikes(),
        'num_dislikes'  => $advertisement->getNumDislikes(),
        'date_start'    => $advertisement->getDateStart(),
        'date_end'      => $advertisement->getDateEnd()
    );
}