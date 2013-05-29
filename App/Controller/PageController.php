<?php
namespace App\Controller;

use App\Model\Registry;

class PageController extends AbstractController
{
    /** @var \App\Model\Service\Pages */
    private $pageService;

    public function _prepare() {
        $this->pageService = \App\Model\Registry::Singleton("\App\Model\Service\Pages");
    }

    public function View($id) {
        $page = $this->pageService->getById($id);
        if (!empty($page)) {
            $this->block("main", "Pages/Simple");
            if ($page['title']) {
                $this->bind("title", $page['title']);
                $this->addBreadCrumb($page['title'], "/Pages/View/".($page['code'] ?: $page['id']));
            }
            $this->bind("leftmenu", $page['code']);
            return $page['text'];
        } else {
            $this->block("main", "Pages/Unknown");
        }
    }


    public function Main()
    {
        $this->block("main", "Pages/Main");
        $this->bind("leftmenu", "Search");
        $this->bind("headersize", "big");

        $this->bind("title", "LAF - запчасти для людей");


        $mans = Registry::Singleton("\App\Model\Service\Tecdoc")->getManufacturers();
        foreach ($mans as &$man) {
            $man['mfa_brand'] = mb_convert_case($man['mfa_brand'], MB_CASE_TITLE);
        }
        return $mans;
//        return "test";
    }


}
