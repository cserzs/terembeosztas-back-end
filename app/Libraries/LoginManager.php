<?php
namespace App\Libraries;

class LoginManager {

    private $session;
    private $loginstatus;

    function __construct() {
        $this->session = \Config\Services::session();
        $this->loginstatus = $this->session->get('LM.loginstatus');
    }

    public function setDisabled() {
        $this->loginstatus = 1;
    }

    public function isLoggedIn() {
        return $this->loginstatus == 1;
    }

    public function login($username, $password) {

        if (\TBConfig::get('login.username') == $username && \TBConfig::get('login.password') == $password) {
            $this->loginstatus = 1;
            $this->session->set('LM.loginstatus', 1);
            return true;
        }
        else {
            $this->loginstatus = 0;
            $this->session->set('LM.loginstatus', 0);
            return false;
        }
    }

    public function logout() {
        $this->loginstatus = 0;
        $this->session->set('LM.loginstatus', 0);
    }
}