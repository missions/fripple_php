<?php
class AppController extends Controller
{
    private $user = null;
    
    public $default_view_class = 'AppLayoutView';

    public function dispatchAction()
    {
        if (!self::isAction($this->action)) {
            // アクション名が予約語などで正しくないとき
            throw new DCException('is invalid');
        }

        if (!method_exists($this, '__call')) {
            if (!method_exists($this, $this->action)) {
                // アクションがコントローラに存在しないとき
                throw new DCException('does not exist');
            }
            $method = new ReflectionMethod($this, $this->action);
            if (!$method->isPublic()) {
                // アクションが public メソッドではないとき
                throw new DCException('is not public');
            }
        }

        // アクションの実行
        $this->{$this->action}();

        $this->render();
    }

    public function start()
    {
        if ($this->user == null) {
            $session_id = Param::get('session_id');
            $device_id = Param::get('device_id');
            $session = AccountSession::get($session_id);
            if (!$session->isValidDeviceId($session_id)) {
                throw new InvalidArgumentException(sprintf('Device ID[%s] is not the recorded device id for Session ID[%s]', $device_id, $session_id));
            }
            $this->user = User::get($session->getUserId());
        }
        return $this->user;
    }
}
