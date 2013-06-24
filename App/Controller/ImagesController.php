<?php
namespace App\Controller;

class ImagesController extends AbstractController
{
    /** @var \App\Model\Service\Images */
    private $imagesService;

    public function _prepare() {
        $this->imagesService = \App\Model\Registry::Singleton("\App\Model\Service\Images");
        $this->addBreadCrumb("Галерея", "/Images/");
        $this->bind("mainmenu", "Images");
    }

    public function Main($page = 1, $order = null, $filter = null)
    {
        $this->block("main", "Images/List");

        $filterBy = [];
        if ($filter) {
            foreach (explode("&", $filter) as $filterGroup) {
                list($param, $value) = explode("=", $filterGroup);
                $filterBy[$param] = $value;
            }
        }

        $this->bind("order", $order);
        $this->bind("filter", $filter);

        return $this->imagesService->GetList($page, $order, $filterBy);
    }

    public function View($id, $maxWidth = 0, $maxHeight = 0) {
        $this->layout("Empty");

        $this->imagesService->getImageResized($id, $maxWidth, $maxHeight);
    }

    public function Info($id) {
        $this->block("main", "Images/Info");

        $this->bind("comments", $this->imagesService->getComments($id));

        return $this->imagesService->GetData($id);
    }

    public function Upload() {
        if (!empty($_FILES['image'])) {
            $this->imagesService->UploadImage($_FILES['image'], $this->request->params['description']);
        }

        $this->redirect("/Images/");

    }

    public function Comments($id, $action = 'View')
    {
        $this->layout("Block");
        $this->block("main", "Blocks/Comments");

        if ($action == "Add") {
            $this->imagesService->addComment($id, $this->request->params['text']);
        }

        return $this->imagesService->getComments($id);
    }
}
