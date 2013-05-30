<?php
namespace App\Controller;

class IdeaController extends AbstractController
{
    /** @var \App\Model\Service\Ideas */
    private $ideaService;

    public function _prepare()
    {
        $this->ideaService = \App\Model\Registry::Singleton("\App\Model\Service\Ideas");
        $this->addBreadCrumb("Идеи и предложения", "/Idea/");
        $this->bind("mainmenu", "Idea");
    }

    public function Main($page = 1)
    {
        $this->block("main", "Idea/List");

        return $this->ideaService->GetList($page);
    }

    public function View($id)
    {
        $this->block("main", "Idea/Info");

        return $this->ideaService->read($id);
    }


    public function Comments($id, $action = 'View')
    {
        $this->layout("Block");
        $this->block("main", "Blocks/Comments");

        if ($action == "Add") {
            $this->ideaService->addComment($id, $this->request->params['text']);
        }

        return $this->ideaService->getComments($id);
    }

    public function Add()
    {
        if ($this->user['flag_admin']) {
            $this->ideaService->create($this->request->params['title'], $this->request->params['text']);
        }
        $this->redirect("/Idea/");
    }

    public function Vote($id, $yes)
    {
        $this->ideaService->registerVote($id, (bool)$yes);

        $this->Redirect("/Idea/#idea_{$id}");
    }

}
