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
        $role           = Param::get('role', User::ROLE_NORMAL);
        try {
            $user_info = array(
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'username'      => $username,
                'password'      => $password,
                'facebook_id'   => $facebook_id,
                'email_address' => $email_address
            );
            $user = User::create($user_info);
        } catch (UserAlreadyExistsException $e) {
            $this->set(get_defined_vars());
            $this->render('user/error_user_already_exists');
        } catch (InvalidArgumentException $e) {
            Log::error($e->getMessage());
            $this->set(get_defined_vars());
            $this->render('error/default');
        }
        $this->set(get_defined_vars());
    }

    public function login()
    {
        $device_id      = Param::get('device_id');
        $username       = Param::get('uname');
        $password       = Param::get('pword', null);
        $facebook_id    = Param::get('fb_id', null);
        try {
            $user = User::getByLoginInfo($username, $password, $facebook_id);
            $session = AccountSession::create($user->id, $device_id);
        } catch (SessionAlreadyExistsException $e) {
            $this->set(get_defined_vars());
            $this->render('user/error_session_already_exists');
        }
        $this->set(get_defined_vars());
    }
}