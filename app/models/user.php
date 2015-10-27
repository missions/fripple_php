<?php
class User extends AppModel
{
    public $id;

    public static function create(array $user_info)
    {
        try {
            $db = DB::conn();
            $user_info['created'] = Time::now();
            $db->insert('user', $user_info);
            return new self($user_info);
        } catch (SimpleDBIException $e) {
            if ($e->isDuplicateEntry()) {
                throw new UserAlreadyExistsException();
            } else {
                Log::error($e->getMessage());
            }
        }
    }

    public static function get($user_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM user WHERE id = ?', array($user_id));
        if (!$row) {
            throw new RecordNotFoundException();
        }
        return new self($row);
    }

    public static function getByFacebookId($facebook_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM user WHERE facebook_id = ? AND facebook_id IS NOT NULL', $facebook_id);
        if (!$row) {
            throw new RecordNotFoundException();
        }
        return new self($row);
    }

    public static function createIfNotExists($user_info)
    {
        try {
            $user = self::getByFacebookId($user_info['facebook_id']);
        } catch (RecordNotFoundException $e) {
            $user = self::create($user_info);
        }
        return $user;
    }

    public static function getBySessionId($session_id)
    {
        $session = AccountSession::getById($session_id);
        return self::get($session->user_id);
    }

    public static function getByLoginInfo($username, $password)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM user WHERE username = ? AND password = ?',
            array($username, md5($password)));
        if (!$row) {
            throw new RecordNotFoundException();
        }
        return new self($row);
    }
}