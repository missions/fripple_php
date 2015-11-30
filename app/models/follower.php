<?php
class Follower extends AppModel
{
    private $user;
    private $restaurant;

    public static function getAllByRestaurantId($restaurant_id)
    {
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM follower WHERE restaurant_id = ?', array($restaurant_id));
        $followers = array();
        foreach ($rows as $row) {
            try {
                $user = User::get($row['user_id']);
                if ($user->isNormal()) {
                    $row['user'] = $user;
                    $followers[] = new self($row);
                }
            } catch (RecordNotFoundException $e) {
                self::delete($restaurant_id, $row['user_id']);
            }
        }
        return $followers;
    }

    public static function getAllByUser(User $user)
    {
        if (!$user->isNormal()) {
            throw new UserRoleNotNormalException(sprintf('Cannot get followers of [%s] user role', $user->getRole()));
        }
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM follower WHERE user_id = ?', array($user->getId()));
        $followers = array();
        foreach ($rows as $row) {
            try {
                $restaurant = Restaurant::get($row['restaurant_id']);
                $row['user'] = $user;
                $row['restaurant'] = $restaurant;
                $followers[] = new self($row);
            } catch (RecordNotFoundException $e) {
                self::delete($row['restaurant_id'], $user->getId());
            }
        }
        return $followers;
    }

    public static function getNumByRestaurantId($restaurant_id)
    {
        $db = DB::conn();
        return (int) $db->value('SELECT COUNT(*) FROM follower WHERE restaurant_id = ?', array($restaurant_id));
    }

    public static function create(Restaurant $restaurant, $user_id)
    {
        $restaurant_id = $restaurant->getId();
        $db = DB::conn();
        $db->begin();
        try {
            $params = array(
                'restaurant_id' => $restaurant_id,
                'user_id'       => $user_id
            );
            $db->insert('follower', $params);
            $num_followers = self::getNumByRestaurantId($restaurant_id);
            $restaurant->updateNumFollowers($num_followers);
            $db->commit();
        } catch (SimpleDBIException $e) {
            $db->rollback();
            throw new UserAlreadyFollowingException(sprintf('User ID [%s] is already a follower of Restaurant ID [%s]', $user_id, $restaurant_id));
        }
    }

    public static function delete(Restaurant $restaurant, $user_id)
    {
        $restaurant_id = $restaurant->getId();
        $db = DB::conn();
        $db->begin();
        try {
            $params = array(
                'restaurant_id' => $restaurant_id,
                'user_id'       => $user_id
            );
            $db->query('DELETE FROM follower WHERE restaurant_id = ? AND user_id = ?', $params);
            $num_followers = self::getNumByRestaurantId($restaurant_id);
            $restaurant->updateNumFollowers($num_followers);
            $db->commit();
        } catch (SimpleDBIException $e) {
            $db->rollback();
            Log::error($e->getMessage());
            throw $e;
        }
    }

    public function getUserId()
    {
        return $this->user_id;
    }

    public function getUser()
    {
        if (!$this->user) {
            $this->user = User::get($this->getUserId());
        }
        return $this->user;
    }

    public function getRestaurantId()
    {
        return $this->restaurant_id;
    }

    public function getRestaurant()
    {
        if (!$this->restaurant) {
            $this->restaurant = Restaurant::get($this->getRestaurantId());
        }
        return $this->restaurant;
    }
}