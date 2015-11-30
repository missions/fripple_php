<?php
class RestaurantController extends AppController
{
    public function index()
    {
        $user = $this->start();
        $restaurant_service = $user->getServiceLocator()->getRestaurantService();
        $is_following_only = Param::get('is_following_only', true);
        $sort_type = Param::get('sort_type', RestaurantService::SORT_TYPE_TRENDING);
        $restaurants = $restaurant_service->getAll($is_following_only);
        $restaurant_service->sortRestaurants($restaurants, $sort_type);
        $this->set(get_defined_vars());
    }

    public function view()
    {
        $user = $this->start();
        $service_locator = $user->getServiceLocator();
        $restaurant_id = Param::get('restaurant_id');
        $restaurant = $service_locator->getRestaurantService()->getByRestaurantId($restaurant_id);
        $active_advertisements = $service_locator->getAdvertisementService()->getByRestaurantId($restaurant_id);
        $followers = $restaurant->getFollowingUsers();
        $this->set(get_defined_vars());
    }

    public function create()
    {
        $user = $this->start();
        $name = Param::get('name');
        $new_restaurant = $user->getServiceLocator()->getRestaurantService()->initialSetup($name);
        $this->set(get_defined_vars());
    }

    public function update()
    {
        $user = $this->start();
        $restaurant_id = Param::get('restaurant_id');
        $restaurant = $user->getServiceLocator()->getRestaurantService()->getByRestaurantId($restaurant_id);
        $params = array(
            'name'           => Param::get('name'),
            'contact_number' => Param::get('contact_number'),
            'description'    => Param::get('description', null),
            'longitude'     => Param::get('longitude', null),
            'latitude'       => Param::get('longitude', null)
        );
        $restaurant->update($params);
        $this->set(get_defined_vars());
    }

    public function set_location()
    {
        $user = $this->start();
        $restaurant_id = Param::get('restaurant_id');
        $restaurant = $user->getServiceLocator()->getRestaurantService()->getByRestaurantId($restaurant_id);
        $params = array(
            'longitude'     => Param::get('longitude'),
            'latitude'       => Param::get('longitude')
        );
        $restaurant->update($params);
        $this->set(get_defined_vars());
    }
}