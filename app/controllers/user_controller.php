<?php
class UserController extends AppController
{
    public function register()
    {
        $first_name     = Param::get('fname');
        $last_name      = Param::get('lname');
        $username       = Param::get('uname');
        $password       = Param::get('pword', null);
        $facebook_id    = Param::get('fb_id', null);
        $email_address  = Param::get('email_add');
        try {
            $user_info = array(
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'username'      => $username,
                'password'      => $password,
                'facebook_id'   => $facebook_id,
                'email_address' => $email_address
            );
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