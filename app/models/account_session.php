<?php
class AccountSession extends AppModel
{
    public $id;
    public $user_id;
    public $device_id;

    public static function get($id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM account_session WHERE id = ?', array($id));
        if (!$row) {
            throw new RecordNotFoundException(sprintf('Account session id [%s] does not exist', $id));
        }
        return new self($row);
    }

    public static function create($user_id, $device_id)
    {
        try {
            $db = DB::conn();
            $params = array(
                'user_id'   => $user_id,
                'device_id' => $device_id,
                'date_end'  => Time::now('+1 year')
            );
            $db->insert('user', $params);
            $params['id'] = $db->lastInsertId();
            return new self($params);
        } catch (SimpleDBIException $e) {
            throw new SessionAlreadyExistsException(sprintf('Session for %s, device_id %s already exists', $user_id, $device_id));
        }
    }

    public function getId()
    {
        return (int) $this->id;
    }

    public function getUserId()
    {
        return (int) $this->user_id;
    }

    public function getDeviceId()
    {
        return $this->device_id;
    }

    public function getDateStart()
    {
        return $this->date_start;
    }

    public function getDateEnd()
    {
        return $this->date_end;
    }

    public function isActive()
    {
        return Time::between($this->getDateStart(), $this->getDateEnd());
    }

    public function isValidDeviceId($device_id)
    {
        return $device_id == $this->getDeviceId();
    }

    public function delete()
    {
        try {
            $db = DB::conn();
            $db->query('DELETE FROM account_session WHERE id = ?', array($this->getId()));            
        } catch (SimpleDBIException $e) {
            Log::error($e->getMessage());
        }
    }
}