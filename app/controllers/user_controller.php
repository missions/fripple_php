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
                'email_address' => $email_address,
                'role'          => $role
            );
            $user = User::create($user_info);
        } catch (UserAlreadyExistsException $e) {
            $this->set(get_defined_vars());
            $this->render('user/error_user_already_exists');
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
            if ($user->isBanned()) {
                throw new UserBannedException();
            }
            $session = AccountSession::create($user->getId(), $device_id);
        } catch (SessionAlreadyExistsException $e) {
            $this->set(get_defined_vars());
            $this->render('user/error_session_already_exists');
        }
        $this->set(get_defined_vars());
    }

    public function fb_connect()
    {
        $user = $this->start();
        $facebook_id = Param::get('facebook_id');
        $user->connectToFacebook($facebook_id);
        $this->set(get_defined_vars());
    }

    public function update()
    {
        $user = $this->start();
        $first_name     = Param::get('fnamamse');
        $last_name      = Param::get('lname');
        $username       = Param::get('uname');
        $password       = Param::get('pword', null);
        $email_address  = Param::get('email_add');
        try {
            $params = array(
                'first_name'    => $first_name,
                'last_name'     => $last_name,
                'username'      => $username,
                'password'      => $password,
                'email_address' => $email_address
            );
            $user = $user->update($params);
        } catch (UserAlreadyExistsException $e) {
            $this->set(get_defined_vars());
            $this->render('user/error_user_already_exists');
        }
        $this->set(get_defined_vars());
    }

    public function logout()
    {
        $account_session = $this->getSession();
        $user = User::get($account_session->getUserId());
        if ($account_session->isActive()) {
            $account_session->delete();
        }
        $this->set(get_defined_vars());
    }
}