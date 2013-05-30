<?php
namespace App\Controller;

use App\Model\Registry;

class NewsController extends AbstractController
{
    /** @var \App\Model\Service\News */
    private $newsService;

    public function _prepare() {
        $this->newsService = \App\Model\Registry::Singleton("\App\Model\Service\News");
    }

    public function View($id) {
    }


    public function Main($page = 1)
    {
        $this->block("main", "News/List");
        $this->bind("mainmenu", "Main");
        $this->addBreadCrumb("Новости", "/News/");

        $this->bind("title", "LAF Intranet - новости");

        $this->bind("news", $this->newsService->getPaged($page));
    }

    public function Comments($id, $action = 'View') {
        $this->layout("Block");
        $this->block("main", "Blocks/Comments");

        if ($action == "Add") {
            $this->newsService->addComment($id, $this->request->params['text']);
        }

        return $this->newsService->getComments($id);
    }

    public function Add() {
        if ($this->user['flag_admin']) {
            $this->newsService->create($this->request->params['title'], $this->request->params['text']);
        }
        $this->redirect("/News/");
    }


}
