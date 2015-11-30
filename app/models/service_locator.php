<?php
class ServiceLocator
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getRestaurantService()
    {
        return new RestaurantService($this->user);
    }

    public function getMenuService()
    {
        return new MenuService($this->user);
    }

    public function getAdvertisementService()
    {
        return new AdvertisementService($this->user);
    }
}