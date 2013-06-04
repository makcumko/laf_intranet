<?php
namespace App\Controller;

use App\Model\Registry;

class UserController extends AbstractController
{
    /**
     * @var \App\Model\Service\Users
     */
    private  $userService;

    public function _prepare() {
        $this->userService = Registry::Singleton("\App\Model\Service\Users");
        $this->addBreadCrumb("Пользователь", "/User/");
        $this->bind("mainmenu", "User");
    }

    public function Login($login, $password = "") {
        $this->addBreadCrumb("Вход", "/User/Login");
        $this->block("main", "User/Login");

        if ($login) {
            if ($this->userService->Login($login, $password)) {
                $this->redirect($_SERVER['HTTP_REFERER'] ?: "/");
                return true;
            } else {
                $this->redirect("/User/Wronglogin");
                return false;
            }
        }
        if ($this->user['id']) {
            $this->redirect("/");
        }
    }

    public function Logout() {
        $this->userService->Logout();
        $this->redirect($_SERVER['HTTP_REFERER'] ?: "/");
        return true;
    }

    public function Wronglogin() {
        if ($this->user['id']) {
            $this->redirect("/");
        } else {
            $this->addBreadCrumb("Вход", "/User/Login");
            $this->block("main", "User/Login");
            $this->bind("error", "Неправильный логин или пароль");
        }
    }

    public function Register() {
        $this->block("main", "User/Register");
        $this->addBreadCrumb("Регистрация", "/User/Register");
        $this->bind("form", $this->request->params);

        if ($this->user['id']) {
            $this->redirect("/");
        } elseif ($this->request->params['login']) {
            if ($this->request->params['name']) {
                if ($this->request->params['password'] != $this->request->params['password2']) {
                    throw new \Exception("Пароли не совпадают");
                }

                $this->userService->Register(
                    $this->request->params['login'],
                    $this->request->params['name'],
                    $this->request->params['password']
                );

                $this->redirect("/User/RegisterPending");
            }
            else {
                throw new \Exception("Заполните всю форму");
            }

        }
    }

    public function RegisterPending() {
        $this->block("main", "User/RegisterPending");
    }

    public function Restore($email = "") {
        $this->block("main", "User/Restore");
        $this->addBreadCrumb("Восстановление доступа", "/User/Restore");

        if ($email) {
            $code = $this->userService->GetRestoreLink($email);

            Registry::Singleton("\App\Model\Service\Mailer")->SendTemplate(
                $email,
                "Восстановление доступа к сайту intranet.laf24.ru", "Mails/User/RestoreAccess",
                ["url" => $_SERVER['HTTP_HOST']."/User/Reset/".$code, "code" => $code]
            );
            return "Письмо с кодом восстановления было успешно выслано на адрес {$email}";
        }
    }

    public function Reset($code = "") {
        $this->block("main", "User/Reset");
        $this->addBreadCrumb("Восстановление доступа", "/User/Restore");

        if ($code) {
            $this->userService->AuthByHash($code);
            $this->redirect("/User/Profile");
            return "Вы вошли под вашим аккаунтом. Не забудьте сменить пароль пользователю";
        }
    }



    public function Main() {
//        return $this->callController("UserController", "Profile");
        $id = $this->user['id'];

        $this->block("main", "User/Info");
        $user = $this->userService->GetInfo($id);
        $this->addBreadCrumb($user['fullname'], "/Staff/View/{$user['id']}");
        $this->bind("contactTypes", $this->userService->contactTypes);
        return $user;
    }


    public function Profile() {
        if (!$this->user['id']) {
            $this->redirect("/");
        }

        $this->block("main", "User/Edit");
        $user = $this->userService->GetInfo($this->user['id']);
        $this->addBreadCrumb($user['fullname'], "/Staff/View/{$user['id']}");

        $this->bind("departments", $this->userService->departmentGateway->filter([], ["name" => "ASC"]));
        $this->bind("contactTypes", $this->userService->contactTypes);

        return $user;

//        return $this->callController("StaffController", "Edit", $this->user['id']);
    }

    public function UpdateProfile() {
        $params = $this->request->params;
        $params['id'] = $this->user['id'];

//
        try {
            $this->userService->Update($params);
            $this->redirect("/User/Profile");
        } catch (\Exception $e) {
            $this->block("main", "User/Profile");
            $this->addBreadCrumb("Личный кабинет", "/User/Profile");
            $this->bind("leftmenu", "Profile");
            $this->bind("error", $e->getMessage());
            return $params;
        }
    }

}
