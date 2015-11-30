<?php
class AppController extends Controller
{
    private $user = null;
    private $session = null;
    
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

        try {
            $this->set(array('module_name' => sprintf('%s/%s', $this->name, $this->action)));
            // アクションの実行
            $this->{$this->action}();

            $this->render();
        } catch (InvalidArgumentException $e) {
            Log::error($e->getMessage());
            $defined_vars = get_defined_vars();
            $defined_vars['stack_trace'] = $e->getMessage();
            $this->set($defined_vars);
            $this->render('error/default');
            return;
        } catch (RecordNotFoundException $e) {
            Log::error($e->getMessage());
            $defined_vars = get_defined_vars();
            $defined_vars['stack_trace'] = $e->getMessage();
            $this->set($defined_vars);
            $this->render('error/default');
            return;
        } catch (UserBannedException $e) {
            $this->set(get_defined_vars());
            $this->render('user/error_is_banned');
            return;
        } catch (SessionNotActiveException $e) {
            $this->set(get_defined_vars());
            $this->render('user/error_inactive_session');
            return;
        }
    }

    public function start()
    {
        if ($this->user == null) {
            $device_id = Param::get('device_id');
            $session = $this->getSession();
            if (!$session->isValidDeviceId($device_id)) {
                throw new InvalidArgumentException(sprintf('Device ID[%s] is not the recorded device id for Session ID[%s]', $device_id, $session_id));
            } elseif (!$session->isActive()) {
                throw new SessionNotActiveException();
            }
            $this->user = User::get($session->getUserId());
            if ($this->user->isBanned()) {
                throw new UserBannedException();
            }
        }
        return $this->user;
    }

    public function getSession()
    {
        if ($this->session == null) {
            $session_id = Param::get('session_id');
            $this->session = AccountSession::get($session_id);
        }
        return $this->session;
    }
}
