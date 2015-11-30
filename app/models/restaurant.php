<?php
class Restaurant extends AppModel
{
    protected $followers;

    public static function getAll()
    {
        $db = DB::conn();
        $restaurants = array();
        $rows = $db->rows('SELECT * FROM restaurant');
        foreach ($rows as $row) {
            $restaurants[] = new self($row);
        }
        return $restaurants;
    }

    public static function get($restaurant_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM restaurant WHERE id = ?', array($restaurant_id));
        if (!$row) {
            throw new RecordNotFoundException(sprintf('Restaurant ID [%s] does not exist', $restaurant_id));
        }
        return new self($row);
    }

    public static function getByName($restaurant_name)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM restaurant WHERE name = ?', array($restaurant_name));
        if (!$row) {
            throw new RecordNotFoundException(sprintf('Restaurant with name [%s] does not exist', $restaurant_name));
        }
        return new self($row);
    }

    public static function create($name, $admin_id)
    {
        $db = DB::conn();
        try {
            $params = array(
                'name' => $name,
                'admin_id' => $admin_id,
                'created' => Time::now()
            );
            $db->insert('restaurant', $params);
            $params['id'] = $db->lastInsertId();
            return new self($params);
        } catch (SimpleDBIException $e) {
            throw new RestaurantNameAlreadyExistsException(sprintf('Restaurant with name [%s] already exists'), $name);
        }
    }

    public function update(array $params)
    {
        $db = DB::conn();
        if (isset($params['name']) && $this->isNameExisting($params['name'])) {
            throw new RestaurantNameAlreadyExistsException(sprintf('Restaurant with name [%s] already exists'), $params['name']);
        }
        $db->update('restaurant', $params, array('id' => $this->getId()));
        $this->set($params);
    }

    public function getId()
    {
        return (int) $this->id;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getNumFollowers()
    {
        return (int) $this->num_followers;
    }

    public function updateNumFollowers($num_followers)
    {
        $db = DB::conn();
        $params = array('num_followers' => $num_followers);
        $db->update('restaurant', $params, array('id' => $this->getId()));
        $this->set($params);
    }

    public function getFollowingUsers()
    {
        $followers = Follower::getAllByRestaurantId($this->getId());
        $users = array();
        foreach ($followers as $follower) {
            $users[] = $follower->getUser();
        }
        return $users;
    }

    public function getNumMenuLikes()
    {
        return (int) $this->num_menu_likes;
    }

    public function getNumMenuDislikes()
    {
        return (int) $this->num_menu_dislikes;
    }

    public function updateNumMenuFeedback($num_feedback, $is_liked)
    {
        $db = DB::conn();
        $column_name = 'num_menu_dislikes';
        if ($is_liked) {
            $column_name = 'num_menu_likes';
        }
        $params = array($column_name => $num_feedback);
        $db->update('restaurant', $params, array('id' => $this->getId()));
        $this->set($params);
    }

    public function getContactNumber()
    {
        return $this->contact_number;
    }

    public function getAdminId()
    {
        return (int) $this->admin_id;
    }

    public function getAdmin()
    {
        $admin_id = $this->getAdminId();
        $admin = User::get($admin_id);
        if (!$admin->isRestaurantAdmin()) {
            throw new UserRoleNotRestaurantAdminException(sprintf('User ID [%s] is not a restaurant admin', $admin_id));
        }
    }

    public function getLongitude()
    {
        return (float) $this->longitude;
    }

    public function getLatitude()
    {
        return (float) $this->latitude;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getUpdated()
    {
        return $this->updated;
    }

    public function isNameExisting($name)
    {
        $db = DB::conn();
        return (bool) $db->search('restaurant', array('name = ? AND id != ?'), array($name, $this->getId()));
    }
}