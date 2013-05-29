<?php

namespace App\Controller;

abstract class AbstractController
{
    /** var \App\Model\Request */
    protected $request;
    protected $user;
    protected $templateVars = [];

    public function __construct()
    {
        $this->request = \App\Model\Registry::Get("request");
        $this->user = \App\Model\Registry::Get("user");

        if (!$this->user['id'] && !($this->request->controller == "User")) {
            $this->redirect("/User/Login");
        } else {
            $this->layout('Default');
        }
    }

    public function _prepare() {
    }

    public function bind($name, $value) {
        $this->templateVars[$name] = $value;
    }

    public function getVars() {
        return $this->templateVars;
    }

    protected function layout($name) {
        \App\Model\Registry::Set("template_layout", "Layout/{$name}");
    }

    protected function block($name, $template) {
        $blocks = \App\Model\Registry::Get("template_blocks");
        $blocks[$name] = $template;
        \App\Model\Registry::Set("template_blocks", $blocks);
    }

    protected function addBreadCrumb($title, $href) {
        $breadCrumbs = \App\Model\Registry::Get("template_breadcrumbs");
        $breadCrumbs[] = ["title" => $title, "href" => $href];
        \App\Model\Registry::Set("template_breadcrumbs", $breadCrumbs);
    }

    protected function redirect($url) {
        if ($this->request->format == 'html') {
            ob_end_clean();
            header("Location: ".$url);
            die();
        }
    }

    protected function callController($controllerName, $methodName = "Main") {
        $controllerName = "\\App\\Controller\\{$controllerName}";
        /** @var AbstractController  */
        $controller = new $controllerName();
        $controller->_prepare();
        $result = call_user_func_array([$controller, $methodName], array_slice(func_get_args(), 2));
        $this->templateVars = $controller->templateVars;
        return $result;
    }

    public function Main() {
    }

}