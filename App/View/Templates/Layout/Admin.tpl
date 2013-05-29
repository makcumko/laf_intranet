<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="utf-8" />
    <!--[if IE]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
    <title>LAF</title>
    <meta name="keywords" content="" />
    <meta name="description" content="" />
    <script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>
    <script type="text/javascript" src="/js/jquery.js"></script>

    <link rel="stylesheet" href="/css/admin.css" type="text/css" media="screen, projection" />
    <link rel="stylesheet" href="/css/catalog.css" type="text/css" media="screen, projection" />
    <script type="text/javascript" src="/js/common.js"></script>
    <script type="text/javascript" src="/js/catalog.js"></script>

    <script type="text/javascript" src="/js/cart.js"></script>
    <script type="text/javascript" src="/js/garage.js"></script>

    {*<script type="text/javascript" src="/js/ckeditor/ckeditor.js"></script>*}
    <script type="text/javascript" src="/js/tinymce/tinymce.min.js"></script>

</head>

<body>
<div id="wrapper">

    <div id="title">Laf24.ru control panel</div>

    <div id="topmenu">
        <ul>
            <li {if $leftmenu == 'Page'}class="active"{/if}><a href="/Admin/Pages">Страницы сайта</a></li>
            <li {if $leftmenu == 'Catalog'}class="active"{/if}><a href="/Admin/Catalog">Марки и автомобили</a></li>
            <li {if $leftmenu == 'AssemblyGroups'}class="active"{/if}><a href="/Admin/CatalogAG/">Сборочные группы</a></li>
            <li {if $leftmenu == 'Articles'}class="active"{/if}><a href="/Admin/CatalogArticles/">Детали</a></li>
            <li {if $leftmenu == 'Brands'}class="active"{/if}><a href="/Admin/CatalogBrands/">Производители</a></li>
            <li {if $leftmenu == 'Import'}class="active"{/if}><a href="/Admin/CatalogImport/">Импорт</a></li>
        {*<li {if $leftmenu == 'Users'}class="active"{/if}><a href="/Admin/Users">Пользователи</a></li>*}
        </ul>
        <div class="clearfix"></div>
    </div>

    <section id="middle">

        <div id="container">
            <div id="content">
                {include "Blocks/Breadcrumbs.tpl"}
                <br clear="all"/>
                {$__block_main}
            </div>
        </div>

    </section><!-- #middle-->

</div><!-- #wrapper -->

</body>
</html>
