<?php
namespace App\Controller;

class DefaultController extends AbstractController
{
    /** @var \App\Model\Service\Users */
    private $userService;

    public function _prepare() {
        $this->userService = \App\Model\Registry::Singleton("\App\Model\Service\Users");
    }

    public function Main()
    {
        return $this->callController("NewsController", "Main");
    }

    public function Error(\Exception $e) {
        $this->block("main", "Error");
        $this->bind("errors", [['code' => $e->getCode(), 'text' => $e->getMessage()]]);
    }

}
