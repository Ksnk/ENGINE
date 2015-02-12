<?php
/**
 * система авторизации как она есть
 */

class ENGINE_user extends engine_plugin
{

    static function get_install_info()
    {
        return array(
            'aliace' => 'User',
            'external.options' => array(
                'login' => 'cookie|' . (60 * 60 * 24 * 30), // 30 дней для куки
                'USER' => 'session',
                'past_browser_agent' => 'session',
            ),
            'engine.interfaces' => array(
                'user_find' => array('User','user_find'),
                'has_rights' => array('Rights', 'has_rights'),
            ),
            'engine.event_handler' => array(
                'INITIALIZE' => array(
                    'auth_check'=>array('User', 'auth_check'),
                ),
            )
        );
    }


    /**
     * Функция - затычка для отладки системы авторизации
     * TODO: заткнуть как-нибудь
     * @param $login
     * @param $password
     * @return array
     */
    function user_find($login, $password)
    {
        return array('id' => 1, 'name' => $login, 'password' => $password);

    }

    /**
     *
     * проверка, что юзер не сменил броузер агент. Можно завязаться на IP
     * @static
     *
     */
    protected function check_if_user_still_the_same()
    {
        if (ENGINE::option('past_browser_agent') == $_SERVER['HTTP_USER_AGENT']) {
            return true;
        } else
            return false;
    }

    /**
     * аварийное действие при неверной аутентификации
     * @static
     *
     */
    protected function do_relogin($msg)
    {
        ENGINE::error($msg);
        ENGINE::set_option('action', 'relogin');
    }

    /**
     * изготовление хеша для хранения в базе
     * @param $login
     * @param $password
     * @return string
     */
    function pack_login_password_into_hash($login, $password)
    {
        return md5($login . '|' . $password);
    }

    /**
     * изготовление хеша для хранения в базе
     * @param $login
     * @param $password
     * @return string
     */
    function pack_hash_into_cookie($user)
    {
        return $user['id'] . '|' . md5($user['hash']);
    }

    function do_user_logged_in($user)
    {
        ENGINE::set_option(array(
            'past_browser_agent' => $_SERVER['HTTP_USER_AGENT'],
            'USER' => $user
        ));
    }

    /**
     * проверка куки логина
     * @static
     *
     */
    function do_login_by_cookie($cookie)
    {
        list($id, $hash, $x) = explode('|', $cookie . '||', 1);
        $user = $this->find_by_id($id);
        if (empty($user))
            return false;
        if ($hash != $this->pack_hash_into_cookie($user))
            return false;
        return true;
    }

    function do_login($login, $password)
    {
        $login = $_POST[$login];
        $password = $_POST[$password];
        $user = $this->user_find($login, $password);
        if (!empty($user))
            $this->do_user_logged_in($user);
        else
            $this->do_relogin('incorrect password');
    }

    /**
     * функция- обработчик события INITIALIZE
     */
    function auth_check()
    {
        //$user='',$password='',$saveincookie=LOGIN_SAVEINCOOKIE) {
        $login_user = ENGINE::option('USER');
        ///////////////////////////////////////////////////////////
        if (!empty($login_user) && !$this->check_if_user_still_the_same()) {
            $this->do_relogin('сессия устарела, введите имя-пароль');
            return;
        }

        $login_cookie = ENGINE::option('login_cookie_name', 'login');
        if (empty($login_user) && ('' !== ENGINE::option($login_cookie))) {
            if ($this->do_login_by_cookie(ENGINE::option($login_cookie)))
                return;
        }

    }
}