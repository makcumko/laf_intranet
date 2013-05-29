<?php
namespace App\Model\Service;

class Users extends AbstractService {
    /** @var \App\Model\Gateway\Users */
    public $userGateway;

//    const SALT = "cn(*QNWcry-Q|`";
    const SALT = ""; // пока так для простоты

    function __construct() {
        $this->userGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Users");
        parent::__construct();
    }

    public function Login($login, $password) {
        $hash = md5($password.self::SALT);

        $this->user = current($this->userGateway->filter(["login" => $login, "password" => $hash]));

        if (!empty($this->user)) {
            $_SESSION['user_id'] = $this->user['id'];
            \App\Model\Registry::Set("user", $this->user);
            return true;
        } else {
            return false;
        }
    }

    public function Logout() {
        unset($_SESSION['user_id']);
        \App\Model\Registry::Set("user", null);
    }

    public function Register($login, $name, $password) {
        if (sizeof($this->userGateway->filter(['login' => $login])) > 0) {
            throw new \Exception("Пользователь с таким телефоном уже зарегистрирован");
        }

        $data = [
            "login" => $login,
            "fullname" => $name,
            "password" => md5($password.self::SALT),
        ];
        $this->userGateway->insert($data);

//        $this->Login($login, $password);
    }


    public function Update($params) {
        if (!$params['id']) {
            throw new \Exception("Неизвестный пользователь");
        }

        $data = [
            "phonecode" => $params['phonecode'],
            "phonenum" => $params['phonenum'],
            "email" => $params['email'],
            "name" => $params['username'],
            "flag_notify_email" => $params['flag_notify_email'] ? 1 : 0,
            "flag_notify_sms" => $params['flag_notify_sms'] ? 1 : 0
        ];

        if ($params['password']) {
            if ($params['password'] != $params['password2']) {
                throw new \Exception("Пароли не совпадают");
            }
            $data['password'] = md5($params['password'].self::SALT);
        }

        $this->userGateway->update($params['id'], $data);
    }

    public function GetRestoreLink($email) {
        $user = current($this->userGateway->filter(['email' => $email]));
        if (empty($user)) throw new \Exception("Нет зарегистрированного пользователя с таким e-mail");

        $hash = md5($user['email'].$user['password'].self::SALT);
//        return $user['id']."/".$hash;
        return $hash;
    }

    public function AuthByHash($hash) {
        $sql = "SELECT *
                FROM users
                WHERE flag_deleted = 0
                    AND MD5(CONCAT(email, password, :salt:)) = :hash:";
        $user = $this->db->query($sql, ["salt" => self::SALT, "hash" => $hash], \App\Model\DB\AbstractDBAdapter::FETCH_LINE);
        if (empty($user)) throw new \Exception("Невалидный код восстановления");
        $_SESSION['user_id'] = $user['id'];
        $this->GetFromSession();
    }

    public function GetFromSession() {
        if (isset($_SESSION['user_id'])) {
            $id = $_SESSION['user_id'];
            $this->user = $this->userGateway->read($id);
            \App\Model\Registry::Set("user", $this->user);
        }
    }


}
