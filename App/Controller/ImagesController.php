<?php
namespace App\Controller;

class ImagesController extends AbstractController
{
    /** @var \App\Model\Service\Images */
    private $imagesService;

    public function _prepare() {
        $this->imagesService = \App\Model\Registry::Singleton("\App\Model\Service\Images");
        $this->addBreadCrumb("Галерея", "/Staff/");
    }

    public function Main($page = 1)
    {
        $this->block("main", "User/StaffList");

        return $this->userService->GetStaff($page);
    }

    public function View($id, $maxWidth = 0, $maxHeight = 0) {
        $this->layout("Empty");

        $this->imagesService->getImageResized($id, $maxWidth, $maxHeight);
    }
}
