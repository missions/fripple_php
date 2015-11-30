<?php
class RestaurantService
{
    const SORT_TYPE_TRENDING          = 1;
    const SORT_TYPE_NEWEST            = 2;
    const SORT_TYPE_MOST_FOLLOWERS    = 3;
    
    private $user;
    private $following_restaurants;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getAll($is_following_only = true)
    {
        if ($is_following_only) {
            return $this->getFollowedRestaurants();
        }
        return Restaurant::getAll();
    }

    public function getByRestaurantId($restaurant_id)
    {
        return Restaurant::get($restaurant_id);
    }

    public function getFollowedRestaurants()
    {
        if (!$this->following_restaurants) {
            $follower_records = Follower::getAllByUser($this->user);
            foreach ($follower_records as $follower_record) {
                $this->following_restaurants[] = $follower_record->getRestaurant();
            }
        }
        return $this->following_restrants;
    }

    public function getFollowedRestaurantIds()
    {
        $following_restaurants = $this->getFollowedRestaurants();
        $following_restaurant_ids = array();
        foreach ($following_restaurants as $following_restaurant) {
            $following_restaurant_ids[] = $following_restaurant->getId();
        }
        return $following_restaurant_ids;
    }

    public function sortRestaurants(&$restaurants, $type)
    {
        $sort_data = array();
        $sort_id = array();

        foreach ($restaurants as $key => $restaurant) {
            switch ($type) {
            case self::SORT_TYPE_TRENDING:
                $sort_data[$key] = $restaurant->getNumMenuLikes();
                break;
            case self::SORT_TYPE_NEWEST:
                $sort_data[$key] = strtotime($restaurant->getUpdated());
                break;
            case self::SORT_TYPE_MOST_FOLLOWERS:
                $sort_data[$key] = $restaurant->getNumFollowers();
                break;
            default:
                throw new InvalidArgumentException(sprintf('Invalid type [%s] for sorting restaurants', $type));
                break;
            }
            $sort_id[$key] = $restaurant->getId();
        }

        array_multisort($restaurants, $sort_data, SORT_DESC, $sort_id, SORT_DESC);
    }

    public function follow($restaurant_id)
    {
        if (!$this->user->isNormal()) {
            throw new UserRoleNotNormalException(sprintf('User ID [%s] is not a normal user', $this->user->getId()));
        }
        $restaurant = $this->getByRestaurantId($restaurant_id);
        Follower::create($restaurant, $this->user->id);
    }

    public function unfollow($restaurant_id)
    {
        $restaurant = $this->getByRestaurantId($restaurant_id);
        Follower::delete($restaurant, $this->user->id);
    }

    public function initialSetup($name)
    {
        $user_id = $this->user->getId();
        if (!$this->user->isRestaurantAdmin()) {
            throw new UserRoleNotRestaurantAdminException(sprintf('Cannot setup restaurant, User [%s] is not a restaurant admin', $user_id));
        }
        return Restaurant::create($name, $user_id);
    }
}