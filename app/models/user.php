<?php
class User extends AppModel
{
    const MIN_LEN_USERNAME = 6;
    const MAX_LEN_USERNAME = 16;

    const MIN_LEN_PASSWORD = 8;
    const MAX_LEN_PASSWORD = 16;

    const ROLE_NORMAL           = 0;
    const ROLE_RESTAURANT_ADMIN = 500;
    const ROLE_BANNED           = 501;

    const DEFAULT_IS_ACTIVE = 1;

    public $validation = array(
        'username' => array(
            'length' => 'validate_between', self::MIN_LEN_USERNAME, self::MAX_LEN_USERNAME
        ),
        'password' => array(
            'length' => 'validate_between', self::MIN_LEN_PASSWORD, self::MAX_LEN_PASSWORD
        )
    );

    public static function get($user_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM user WHERE id = ?', array($user_id));
        if (!$row) {
            throw new RecordNotFoundException(sprintf('User id %s does not exist', $user_id));
        }
        return new self($row);
    }

    public static function create(array $params)
    {
        $facebook_id = $params['facebook_id'];
        if (!$params['password'] && !$facebook_id) {
            throw new InvalidArgumentException('Either a password/facebook id should be passed to create an account');
        }
        if ($facebook_id && self::isFacebookIdExisting($facebook_id)) {
            throw new FacebookIdAlreadyExistsException(sprintf('Facebook id [%s] is already used by another user', $facebook_id));
        }
        try {
            $db = DB::conn();
            $params['is_active']    = (int) ($params['role'] == self::ROLE_NORMAL);
            $params['password']     = ($params['password']) ? md5($params['password']) : null;
            $params['created']      = Time::now();
            $db->insert('user', $params);
            return new self($params);
        } catch (SimpleDBIException $e) {
            throw new UserAlreadyExistsException();
        }
    }

    public static function getByLoginInfo($username, $password, $facebook_id)
    {
        if (!$password && !$facebook_id) {
            throw new InvalidArgumentException(sprintf('Invalid access! Must have a facebook id or a username/password to get login info', $username, $facebook_id));
        }
        $db = DB::conn();
        $user = $db->row('SELECT * FROM user WHERE username = ? AND password = ? AND facebook_id = ?',
            array($username, $password, $facebook_id));
        if (!$user) {
            $login_info = array();
            if ($username) {
                $login_info[] = sprintf('username [%s]', $username);
            }
            if ($facebook_id) {
                $login_info[] = sprintf('facebook_id [%s]', $facebook_id);
            }
            throw new RecordNotFoundException(sprintf('No existing user record (%s)', implode(', ', $login_info)));
        }       
        return new self($user);
    }

    public function getServiceLocator()
    {
        return new ServiceLocator($this);
    }

    public function getId()
    {
        return (int) $this->id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }    

    public function getUsername()
    {
        return $this->username;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getRole()
    {
        return (int) $this->role;
    }

    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    public function getEmailAddress()
    {
        return $this->email_address;
    }

    public function getRebatePoints()
    {
        return (float) $this->rebate_points;
    }

    public function isActive()
    {
        return (bool) $this->is_active;
    }

    public function isNormal()
    {
        return $this->getRole() == self::ROLE_NORMAL;
    }

    public function isBanned()
    {
        return $this->getRole() == self::ROLE_BANNED;
    }

    public function isRestaurantAdmin()
    {
        return $this->getRole() == self::ROLE_RESTAURANT_ADMIN;
    }

    public function isParamExisting($params, $param_condition = 'OR') {
        $db = DB::conn();
        $where = array();
        foreach ($params as $key => $value) {
            $where[] = sprintf('%s = ?', $key);
            $where_params[] = $value;
        }
        $where = implode($param_condition.' ', $where);
        $where_params = array($this->getId());
        return (bool) $db->search('user', sprintf('%s AND id != ?', $where), $where_params);
    }

    public function connectToFacebook($facebook_id)
    {
        if ($this->getFacebookId()) {
            throw new UserAlreadyConnectedToFacebookException(sprintf('User [%s] is already connected to facebook!', $this->getId()));
        }
        $db = DB::conn();
        $param = array('facebook_id' => $facebook_id);
        if (self::isParamExisting($param)) {
            throw new FacebookIdAlreadyExistsException(sprintf('Facebook id [%s] is already used by another user', $facebook_id));
        }
        $db->update('user', array($param), array('id' => $this->getId()));
    }

    public function update(array $params)
    {
        try {
            $db = DB::conn();
            $params['password']     = ($params['password']) ? md5($params['password']) : null;
            $db->update('user', $params, array('id' => $this->getId()));
            $this->set($params);
        } catch (SimpleDBIException $e) {
            throw new UserAlreadyExistsException('Username/Email Address is already taken');
        }
    }
}