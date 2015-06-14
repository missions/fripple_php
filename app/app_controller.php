<?php
class AppController extends Controller
{
    public $default_view_class = 'AppLayoutView';
    public $user = null;

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
        if ($this->user) {
            return $this->user;
        }
        $session_id = Param::get('session_id');
        $this->user = User::getBySessionId($session_id);
        return $this->user;
    }
}
