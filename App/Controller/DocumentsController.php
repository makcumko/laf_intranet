<?php
namespace App\Controller;

class DocumentsController extends AbstractController
{
    /** @var \App\Model\Service\Documents */
    private $documentsService;

    public function _prepare()
    {
        $this->documentsService = \App\Model\Registry::Singleton("\App\Model\Service\Documents");
        $this->addBreadCrumb("Документы", "/Documents/");
        $this->bind("mainmenu", "Documents");
    }

    public function Main($page = 1)
    {
        $this->block("main", "Documents/List");

        return $this->documentsService->GetList($page);
    }


    public function Add() {
        if ($this->user['flag_admin']) {
            // TODO
            $this->documentsService->create($this->request->params['title'], $this->request->params['text']);
        }
        $this->redirect("/Documents/");
    }
}
