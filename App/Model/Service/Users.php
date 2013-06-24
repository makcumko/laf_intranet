<?php
namespace App\Model\Service;

class Users extends AbstractService {
    /** @var \App\Model\Gateway\Users */
    public $userGateway;
    /** @var \App\Model\Gateway\Contacts */
    public $contactGateway;
    /** @var \App\Model\Gateway\Departments */
    public $departmentGateway;

//    const SALT = "cn(*QNWcry-Q|`";
    const SALT = ""; // пока так для простоты
    const STAFF_PAGESIZE = 20;

    public $contactTypes = [
        'Email' => ['name' => 'E-mail', 'placeholder' => 'vasiliy@pupkin.ru', 'required' => true],
        'Phone' => ['name' => 'Телефон', 'placeholder' => '+7-123-4567890', 'required' => true],
        'Internal' => ['name' => 'Внутренний номер', 'placeholder' => '0000', 'required' => false],
        'Skype' => ['name' => 'Skype', 'placeholder' => 'vasiliy.pupkin', 'required' => true],
        'ICQ' => ['name' => 'ICQ', 'placeholder' => '000000000', 'required' => false],
        'Misc' => ['name' => 'Другое', 'placeholder' => 'Произвольный контакт', 'required' => false],
    ];

    function __construct() {
        $this->userGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Users");
        $this->contactGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Contacts");
        $this->departmentGateway = \App\Model\Registry::Singleton("\App\Model\Gateway\Departments");
        parent::__construct();
    }

    public function Login($login, $password) {
        $hash = md5($password.self::SALT);

        $this->user = current($this->userGateway->filter(["login" => $login, "password" => $hash, "flag_approved" => 1]));

        if (!empty($this->user)) {
            $_SESSION['user_id'] = $this->user['id'];
            \App\Model\Registry::set("user", $this->user);

            setcookie('userinfo_name', $this->user['fullname'], time()+60*60*24*30, '/');
            setcookie('userinfo_login', $this->user['login'], time()+60*60*24*30, '/');
            setcookie('userinfo_password', $this->user['password'], time()+60*60*24*30, '/');

            return true;
        } else {
            return false;
        }
    }

    public function Logout() {
        unset($_SESSION['user_id']);
        \App\Model\Registry::Set("user", null);
    }

    public function Register($login, $name, $password, $approve = false) {
        if (sizeof($this->userGateway->filter(['login' => $login])) > 0) {
            throw new \Exception("Пользователь с таким логином уже зарегистрирован");
        }

        $data = [
            "login" => $login,
            "fullname" => $name,
            "password" => md5($password.self::SALT),
            "flag_approved" => (bool)$approve
        ];
        $this->userGateway->insert($data);

//        $this->Login($login, $password);
    }


    public function GetRestoreLink($email) {
        $user = current($this->userGateway->filter(['login' => $email]));
        if (empty($user)) throw new \Exception("Нет зарегистрированного пользователя с таким e-mail");

        $hash = md5($user['login'].$user['password'].self::SALT);
//        return $user['id']."/".$hash;
        return $hash;
    }

    public function AuthByHash($hash) {
        $sql = "SELECT *
                FROM users
                WHERE flag_deleted = 0
                    AND MD5(CONCAT(login, password, :salt:)) = :hash:";
        $user = $this->db->query($sql, ["salt" => self::SALT, "hash" => $hash], \App\Model\DB\AbstractDBAdapter::FETCH_LINE);
        if (empty($user)) throw new \Exception("Невалидный код восстановления");
        $_SESSION['user_id'] = $user['id'];
        $this->GetFromSession();
    }

    public function GetFromSession() {
        if (isset($_SESSION['user_id'])) {
            $id = $_SESSION['user_id'];
            $this->user = $this->userGateway->read($id);
            $this->userGateway->update($id, ['last_action' => date("Y-m-d H:i:s")]);
            \App\Model\Registry::Set("user", $this->user);

            if (empty($_COOKIE['userinfo_name'])) {
                setcookie('userinfo_name', $this->user['fullname'], time()+60*60*24*30, '/');
                setcookie('userinfo_login', $this->user['login'], time()+60*60*24*30, '/');
                setcookie('userinfo_password', $this->user['password'], time()+60*60*24*30, '/');
            }
        }
    }

    public function GetStaff($page = 1) {
        $staff = $this->userGateway->filterPaged([], ["id" => "ASC"], self::STAFF_PAGESIZE, self::STAFF_PAGESIZE * ($page - 1));
        foreach ($staff['items'] as &$user) {
            $user['contacts'] = $this->contactGateway->getByUser($user['id']);
            $user['department'] = $this->departmentGateway->read($user['department_id'])['name'];
        }
        return $staff;
    }

    public function GetInfo($id) {
        $user = $this->userGateway->read($id);
        $user['contacts'] = $this->contactGateway->getByUser($user['id']);
        $user['department'] = $this->departmentGateway->read($user['department_id'])['name'];

        return $user;
    }


}
