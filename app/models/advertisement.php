<?php
class Advertisement extends AppModel
{
    const MAIN_IMAGE_DIR    = IMG_DIR.'advertisement/main/';
    const THUMB_IMAGE_DIR   = IMG_DIR.'advertisement/thumb/';

    public static function get($advertisement_id)
    {
        $db = DB::conn();
        $row = $db->row('SELECT * FROM advertisement WHERE id = ?', array($advertisement_id));
        if (!$row) {
            throw new RecordNotFoundException(sprintf('Advertisement ID [%s] does not exist', $advertisement_id));
        }
        return new self($row);
    }

    public static function getAllByRestaurantId($restaurant_id, $is_active_only)
    {
        $db = DB::conn();
        $rows = $db->search('advertisement', 'restaurant_id = ?', array($restaurant_id));
        $advertisements = array();
        foreach ($rows as $row) {
            if ($is_active_only && !Time::between($row['date_start'], $row['date_end'])) {
                continue;
            }
            $advertisements[] = new self($row);
        }
        return $advertisements;
    }

    public static function getAll()
    {
        $db = DB::conn();
        $rows = $db->rows('SELECT * FROM advertisement');
        $advertisements = array();
        foreach ($rows as $row) {
            if (!Time::between($row['date_start'], $row['date_end'])) {
                continue;
            }
            $advertisements[] = new self($row);
        }
        return $advertisements;
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

    public function getDateStart()
    {
        return $this->date_start;
    }

    public function getDateEnd()
    {
        return $this->date_end;
    }

    public function getCreated()
    {
        return $this->created;
    }

    public function getNumLikes()
    {
        return (int) $this->num_likes;
    }

    public function getNumDislikes()
    {
        return (int) $this->num_dislikes;
    }

    public function updateNumFeedback($num_feedback, $is_liked)
    {
        $db = DB::conn();
        $column_name = 'num_dislikes';
        if ($is_liked) {
            $column_name = 'num_likes';
        }
        $params = array($column_name => $num_feedback);
        $db->update('advertisement', $params, array('id' => $this->getId()));
        $this->set($params);
    }
}