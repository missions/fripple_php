<?php
class AdvertisementFeedback extends AppModel
{
    public static function get($advertisement_id, $user_id)
    {
        $db = DB::conn();
        $feedback = $db->row('SELECT * FROM advertisement_feedback WHERE advertisement_id = ? AND user_id = ?', array($advertisement_id, $user_id));
        if (!$feedback) {
            throw new RecordNotFoundException('There no existing menu feedback for Menu ID [%s] by User ID [%s]', $advertisement_id, $user_id);
        }
        return new self($feedback);
    }

    public static function createIfNotExists($advertisement_id, $user_id, $is_liked)
    {
        $db = DB::conn();
        try {
            $params = array(
                'advertisement_id'  => $advertisement_id,
                'user_id'           => $user_id,
                'is_liked'          => (int) $is_liked
            );
            $db->insert('advertisement_feedback', $params);
            $ad_feedback = new self($params);
        } catch (SimpleDBIException $e) {
            $ad_feedback = self::get($advertisement_id, $user_id);
            $ad_feedback->updateUserFeedback($is_liked);
        }
        $advertisement = $ad_feedback->getAdvertisement();
        $num_feedback = $ad_feedback->getNumFeedback($is_liked);
        $advertisement->updateNumFeedback($num_feedback, $is_liked);
    }

    public function getNumFeedback($is_liked)
    {
        $db = DB::conn();
        return $db->value('SELECT COUNT * FROM advertisement_feedback WHERE advertisement_id = ? AND is_liked = ?', array($this->getAdvertisementId(), $is_liked));
    }

    public function getAdvertisementId()
    {
        return $this->advertisement_id;
    }

    public function getAdvertisement()
    {
        return Advertisement::get($this->getAdvertisementId());
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
            'advertisement_id'  => $this->getAdvertisementId(),
            'user_id'           => $this->getUserId()
        );
        $db->update('advertisement_feedback', array('is_liked' => (int) $is_liked), $where_params);
    }

    public function delete()
    {
        $db = DB::conn();
        $db->query('DELETE FROM advertisement_feedback WHERE advertisement_id = ? AND user_id = ?', array($this->getAdvertisementId(), $this->getUserId()));
    }
}