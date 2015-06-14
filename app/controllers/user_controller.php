<?php
class UserController extends AppController
{
    public function register()
    {
        $user_info = param::getAll();
        try {
            User::create($user_info);
        } catch (UserAlreadyExistsException $e) {
            $this->set(get_defined_vars());
            $this->render('user/error_user_already_exists');
        }
        $this->set(get_defined_vars());
    }

    public function login()
    {
        $user_info = param::getAll();
        $device_id = $user_info['device_id'];
        unset($user_info['device_id']);
        if (isset($user_info['facebook_id'])) {
            $user = User::createIfNotExists($user_info);
        } else {
            $user = User::getByLoginInfo($user_info['username'], $user_info['password']);
        }
        try {
            $session_info = array(
                'user_id'   => $user->id,
                'device_id' => $device_id
            );
            $session = AccountSession::createIfNotExists($session_info);
        } catch (SessionAlreadyExistsException $e) {
            $this->set(get_defined_vars());
            $this->render('user/error_session_already_exists');
        }
        $this->set(get_defined_vars());
    }
}