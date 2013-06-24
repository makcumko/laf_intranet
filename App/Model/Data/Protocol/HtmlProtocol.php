<?php
namespace App\Model\Data\Protocol;

class HtmlProtocol implements AbstractProtocol {
    /** @var \Smarty */
    protected $smarty;
    protected $layout = 'Layout/Default';
    protected $blocks = ['main' => 'Display'];

    public function __construct()
    {
        $this->smarty = new \Smarty( );

        $this->smarty->template_dir = VIEW_DIR.'Templates/';
        $this->smarty->compile_dir  = VIEW_DIR.'Compiled/';
        $this->smarty->cache_dir    = VIEW_DIR.'Cache/';
        $this->smarty->error_unassigned = false;

//        $this->smarty->debugging    = (bool) $debug;
    }

    public function display($data)
    {
        if ($controllerLayout = \App\Model\Registry::Get("template_layout"))
            $this->layout = $controllerLayout;
        if ($controllerBlocks = \App\Model\Registry::Get("template_blocks"))
            foreach ($controllerBlocks as $key=>$val) $this->blocks[$key] = $val;

        $data['_request'] = \App\Model\Registry::Get("request");
        $data['_user'] = \App\Model\Registry::Get("user");
        $data['_breadcrumbs'] = \App\Model\Registry::Get("template_breadcrumbs") ?: [];

        $tpl = $this->smarty->createTemplate("{$this->layout}.tpl");
        foreach ($this->blocks as $block => $template) {
            $tpl->assign("__block_{$block}", $this->fetch("{$template}.tpl", $data));
        }

        if (is_array($data)) $tpl->assign($data);

//        header('Content-type: text/html');

        echo $this->smarty->fetch($tpl);
    }

    public function fetch( $filename, array $vars = [] )
    {
        $tpl = $this->smarty->createTemplate($filename);
        $tpl->assign($vars);
        return $this->smarty->fetch($tpl);
    }

    public function getRequestParams($request) {
        return [];
    }

}