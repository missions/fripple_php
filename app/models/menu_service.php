<?php
class MenuService
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getByMenuId($menu_id)
    {
        return Menu::get($menu_id);
    }

    public function getByRestaurantId($restaurant_id)
    {
        return Menu::getAllByRestaurantId($restaurant_id);
    }

    public function updateFeedback($menu_id, $is_liked)
    {
        $menu_item = $this->getByMenuId($menu_id);
        MenuFeedback::createIfNotExists($menu_item, $this->user->getId(), $is_liked);
    }
}