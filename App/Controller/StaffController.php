<?php
namespace App\Controller;

class StaffController extends AbstractController
{
    /** @var \App\Model\Service\Users */
    private $userService;

    public function _prepare() {
        $this->userService = \App\Model\Registry::Singleton("\App\Model\Service\Users");
        $this->addBreadCrumb("Персонал", "/Staff/");
    }

    public function Main($page = 1)
    {
        $this->block("main", "User/StaffList");

        return $this->userService->GetStaff($page);
    }

    public function Approve($id) {
        if ($this->user['flag_admin']) {
            $this->userService->userGateway->update($id, ['flag_approved' => 1]);
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

            $contacts = [];
            foreach ($data as $key=>$val) {
                preg_match("/^contact_(\w+)_value$/is", $key, $matches);
                if (isset($matches[1])) {
                    $contacts[$matches[1]]['values'] = $val;
                    $contacts[$matches[1]]['comments'] = $data["contact_{$matches[1]}_comment"];
                }
            }
            $this->userService->contactGateway->updateForUser($id, $contacts);

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
                $data['password'] = md5($data['password'].\App\Model\Service\Users::SALT);
            }

            $this->userService->userGateway->update($id, $params);

            $this->redirect("/Staff/View/{$id}");
        }


        $this->bind("departments", $this->userService->departmentGateway->filter([], ["name" => "ASC"]));

        return $user;
    }

}
