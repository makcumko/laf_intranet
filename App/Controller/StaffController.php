<?php
namespace App\Controller;

use App\Model\Registry;

class StaffController extends AbstractController
{
    /** @var \App\Model\Service\Users */
    private $userService;

    public function _prepare() {
        $this->userService = \App\Model\Registry::Singleton("\App\Model\Service\Users");
        $this->addBreadCrumb("Персонал", "/Staff/");
        $this->bind("mainmenu", "Staff");
    }

    public function Main($page = 1)
    {
        $this->block("main", "User/StaffList");

        return $this->userService->GetStaff($page);
    }

    public function Approve($id) {
        if ($this->user['flag_admin']) {
            $this->userService->userGateway->update($id, ['flag_approved' => 1]);
            $user = $this->userService->userGateway->read($id);

            Registry::Singleton("\App\Model\Service\Mailer")->SendTemplate(
                $user['login'],
                "Подтверждение доступа к сайту intranet.laf24.ru", "Mails/User/Approved"
            );
        }

        $this->redirect("/Staff/");
    }

    public function View($id) {
        $this->block("main", "User/Info");
        $user = $this->userService->GetInfo($id);
        $this->addBreadCrumb($user['fullname'], "/Staff/View/{$user['id']}");

        return $user;
    }

    public function Edit($id) {
        if (!$this->user['flag_admin'] && $this->user['id'] != $id) {
            $this->redirect("/Staff/View/{$id}");
        }

        $this->block("main", "User/Edit");
        $user = $this->userService->GetInfo($id);
        $this->addBreadCrumb($user['fullname'], "/Staff/View/{$user['id']}");

        $data = $this->request->params;

        if (isset($data['fullname'])) {

            // contacts
            $contacts = [];
            foreach ($data as $key=>$val) {
                preg_match("/^contact_(\w+)_value$/is", $key, $matches);
                if (isset($matches[1])) {
                    $contacts[$matches[1]]['values'] = $val;
                    $contacts[$matches[1]]['comments'] = $data["contact_{$matches[1]}_comment"];
                }
            }
            $this->userService->contactGateway->updateForUser($id, $contacts);

            // user record
            $params = [
                'login' => $data['login'],
                'fullname' => $data['fullname'],
                'shortname' => $data['shortname'],
                'department_id' => $data['department_id'],
                'about' => $data['about']
            ];
            // merging carefully
            foreach ($params as $key=>$val) $user[$key] = $val;

            if ($data['password']) {
                if ($data['password'] != $data['password2']) {
                    throw new \Exception("Пароли не совпадают");
                }
                $params['password'] = md5($data['password'].\App\Model\Service\Users::SALT);
            }

            // avatar image
            if (!empty($_FILES['avatar'])) {
                /** @var \App\Model\Service\Images */
                $imagesService = \App\Model\Registry::Singleton("\App\Model\Service\Images");
                $avId = $imagesService->UploadImage($_FILES['avatar']);
                if ($avId) $params['avatar_id'] = $avId;
            }

            $this->userService->userGateway->update($id, $params);

            $this->redirect("/Staff/View/{$id}");
        }


        $this->bind("departments", $this->userService->departmentGateway->filter([], ["name" => "ASC"]));

        return $user;
    }

    public function Add() {
        if ($this->user['flag_admin']) {
            $this->userService->Register(
                $this->request->params['login'],
                $this->request->params['login'],
                $this->request->params['login'],
                true
            );
        }
        $this->redirect("/Staff/");
    }

}
