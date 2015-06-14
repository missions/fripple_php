<?php
class AccountSession extends AppModel
{
    public $id;
    public $user_id;
    public $device_id;

    public static function get($session_info)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM account_session WHERE user_id = ? AND device_id = ?',
            array($session_info['user_id'], $session_info['device_id']));
        if (!$row) {
            throw new RecordNotFoundException();
        }
        return new self($row);
    }

    public static function getById($session_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM account_session WHERE id = ?', array($session_id));
        if (!$row) {
            throw new RecordNotFoundException();
        }
        return new self($row);
    }

    public static function create($session_info)
    {
        $session_info['id'] = sprintf('%s%s', substr($session_info['device__id'], 0, 4), date('mYd'));
        try {
            $db = DB::conn();
            $db->insert('user', $session_info);
            return new self($session_info);
        } catch (SimpleDBIException $e) {
            if ($e->isDuplicateEntry()) {
                throw new SessionAlreadyExistsException();
            } else {
                Log::error($e->getMessage());
            }
        }
    }

    public static function createIfNotExists($session_info)
    {
        try {
            $session = self::get($session_info);
        } catch (RecordNotFoundException $e) {
            $session = self::create($session_info);
        }
        return $session;

    }
}