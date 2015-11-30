<?php
class MenuController extends AppController
{
    public function index()
    {
        $user = $this->start();
        $restaurant_id = Param::get('restaurant_id');
        $menu = $user->getServiceLocator()->getMenuService()->getByRestaurantId($restaurant_id);
        $this->set(get_defined_vars());
    }

    public function view()
    {
        $user = $this->start();
        $menu_id = Param::get('menu_id');
        $menu_item = $user->getServiceLocator()->getMenuService()->getByMenuId($menu_id);
        $this->set(get_defined_vars());
    }

    public function update_feedback()
    {
        $user = $this->start();
        $menu_id = Param::get('menu_id');
        $is_liked = Param::get('is_liked');
        $user->getServiceLocator()->getMenuService()->updateFeedback($menu_id, $is_liked);
        $this->set(get_defined_vars());
    }
}