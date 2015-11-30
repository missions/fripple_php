<?php
class MenuFeedback extends AppModel
{
    public static function get($menu_id, $user_id)
    {
        $db = DB::conn();
        $feedback = $db->row('SELECT * FROM menu_feedback WHERE menu_id = ? AND user_id = ?', array($menu_id, $user_id));
        if (!$feedback) {
            throw new RecordNotFoundException('There no existing menu feedback for Menu ID [%s] by User ID [%s]', $menu_id, $user_id);
        }
        return new self($feedback);
    }

    public static function getNumByRestaurantId($restaurant_id, $is_liked)
    {
        $db = DB::conn();
        return $db->value('SELECT COUNT * FROM menu_feedback WHERE restaurant_id = ? AND is_liked = ?', array($restaurant_id, $is_liked));
    }

    public static function createIfNotExists(Menu $menu, $user_id, $is_liked)
    {
        $db = DB::conn();
        try {
            $params = array(
                'restaurant_id' => $menu->getRestaurantId(),
                'menu_id'       => $menu->getId(),
                'user_id'       => $user_id,
                'is_liked'      => (int) $is_liked
            );
            $db->insert('menu_feedback', $params);
        } catch (SimpleDBIException $e) {
            $menu_feedback = self::get($menu->getId(), $user_id);
            $menu_feedback->updateUserFeedback($is_liked);
        }
        $restaurant = $menu->getRestaurant();
        $num_feedback = self::getNumByRestaurantId($restaurant->getId(), $is_liked);
        $restaurant->updateNumMenuFeedback($num_feedback, $is_liked);
    }

    public function getNumFeedback($is_liked)
    {
        $db = DB::conn();
        return $db->value('SELECT COUNT * FROM menu_feedback WHERE menu_id = ? AND is_liked = ?', array($this->getMenuId(), $is_liked));
    }

    public function getRestaurantId()
    {
        return $this->restaurant_id;
    }

    public function getRestaurant()
    {
        return Restaurant::get($this->getRestaurantId());
    }

    public function getMenuId()
    {
        return $this->menu_id;
    }

    public function getMenuItem()
    {
        return Menu::get($this->getMenuId());
    }

    public function getUserId()
    {
        return $this->user_id();
    }

    public function isLiked()
    {
        return $this->is_liked;
    }

    public function updateUserFeedback($is_liked)
    {
        if ($this->isLiked() == $is_liked) {
            $this->delete();
            return;
        }
        $db = DB::conn();
        $where_params = array(
            'menu_id' => $this->getMenuId(),
            'user_id' => $this->getUserId()
        );
        $db->update('menu_feedback', array('is_liked' => (int) $is_liked), $where_params);
    }

    public function delete()
    {
        $db = DB::conn();
        $db->query('DELETE FROM menu_feedback WHERE menu_id = ? AND user_id = ?', array($this->getMenuId(), $this->getUserId()));
    }
}