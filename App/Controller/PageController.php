<?php
namespace App\Controller;

use App\Model\Registry;

class PageController extends AbstractController
{
    /** @var \App\Model\Service\News */
    private $newsService;

    public function _prepare() {
        $this->newsService = \App\Model\Registry::Singleton("\App\Model\Service\News");
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


    public function Main($page = 1)
    {
        $this->block("main", "Pages/Main");
        $this->bind("mainmenu", "Main");

        $this->bind("title", "LAF Intranet - новости");

        $this->bind("news", $this->newsService->getPaged($page));

//        return "test";
    }


}
