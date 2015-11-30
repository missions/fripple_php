<?php
class Menu extends AppModel
{
    const MAIN_IMAGE_DIR    = IMG_DIR.'menu/main/';
    const THUMB_IMAGE_DIR   = IMG_DIR.'menu/thumb/';

    public static function get($menu_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM menu WHERE id = ?', array($menu_id));
        if (!$row) {
            throw new RecordNotFoundException(sprintf('Menu ID [%s] does not exist', $menu_id));
        }
        return new self($row);
    }

    public static function getAllByRestaurantId($restaurant_id)
    {
        $db = DB::conn();
        $rows = $db->search('menu', 'restaurant_id = ?', array($restaurant_id));
        $menu = array();
        foreach ($rows as $row) {
            $menu[] = new self($row);
        }
        return $menu;
    }

    public static function create(array $params)
    {

    }

    public function getId()
    {
        return (int) $this->id;
    }

    public function getRestaurantId()
    {
        return (int) $this->restaurant_id;
    }

    public function getRestaurant()
    {
        return Restaurant::get($this->getRestaurantId());
    }

    public function getFoodName()
    {
        return $this->food_name;
    }

    public function getPrice()
    {
        return (float) $this->price;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getMainImageUrl()
    {
        $main_img_url = sprintf('%smain_%s.png', self::MAIN_IMAGE_DIR, $this->getId());
        return (file_exists($main_img_url)) ? $main_img_url : NO_IMAGE_URL;
    }

    public function getThumbImageUrl()
    {
        $main_img_url = sprintf('%sthumb_%s.png', self::THUMB_IMAGE_DIR, $this->getId());
        return (file_exists($main_img_url)) ? $main_img_url : NO_IMAGE_URL;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }
}