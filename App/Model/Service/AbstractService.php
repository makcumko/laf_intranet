<?php

namespace App\Model\Service;

abstract class AbstractService
{
    /** @var \App\Model\Request */
    protected $request;
    protected $user;

    /** @var \App\Model\DB\AbstractDBAdapter */
    protected $db;

    public function __construct()
    {
        $this->request = \App\Model\Registry::Get("request");
        $this->user = \App\Model\Registry::Get("user");
        $this->db = \App\Model\Registry::Get("lafintranet");
    }

}