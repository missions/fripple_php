<?php
class AdvertisementService
{
    const SORT_TYPE_TRENDING    = 1;
    const SORT_TYPE_NEWEST      = 2; 

    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getByAdvertisementId($advertisement_id)
    {
        return Advertisement::get($advertisement_id);
    }

    public function getAllByRestaurantId($restaurant_id, $is_active_only = true)
    {
        return Advertisement::getAllByRestaurantId($restaurant_id, $is_active_only);
    }

    public function getAll($is_following_only, $is_active_only = true)
    {
        $advertisements = array();
        if (!$is_following_only) {
            $advertisements = Advertisement::getAll();
        } else {
            $restaurant_ids = $this->user->getServiceLocator()->getRestaurantService()->getFollowedRestaurantIds();
            foreach ($restaurant_ids as $restaurant_id) {
                $advertisements[] = $this->getAllByRestaurantId($restaurant_id, $is_active_only);
            }
        }
        return $advertisements;
    }

    public function sortAdvertisements(&$advertisements, $type)
    {
        $sort_data = array();

        foreach ($advertisements as $key => $advertisement) {
            switch ($type) {
            case self::SORT_TYPE_TRENDING:
                $sort_data[$key] = $advertisement->getNumLikes();
                break;
            case self::SORT_TYPE_NEWEST:
                $sort_data[$key] = strtotime($advertisement->getDateStart());
                break;
            default:
                throw new InvalidArgumentException(sprintf('Invalid type [%s] for sorting advertisements', $type));
                break;
            }
            $sort_id[$key] = $advertisement->getId();
        }

        array_multisort($advertisements, $sort_data, SORT_DESC, $sort_id, SORT_DESC);
    }

    public function updateFeedback($advertisement_id, $is_liked)
    {
        AdvertisementFeedback::createIfNotExists($advertisement_id, $this->user->getId(), $is_liked);
    }
}