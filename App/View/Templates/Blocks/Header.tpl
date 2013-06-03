<div class="navbar {*navbar-inverse*} {*navbar-fixed-top*}">
    <div class="navbar-inner">
        <div class="container">
            <a class="brand" href="/">LAF Intranet</a>
            <div class="navbar-content">
                <ul class="nav">
                    <li {if $mainmenu == 'Main'}class="active"{/if}><a href="/">Главная</a></li>
                    <li {if $mainmenu == 'Staff'}class="active"{/if}><a href="/Staff/">Сотрудники</a></li>
                    <li {if $mainmenu == 'Idea'}class="active"{/if}><a href="/Idea/">Предложения</a></li>
                    <li {if $mainmenu == 'Documents'}class="active"{/if}><a href="/Documents/">Документы</a></li>
                    <li {if $mainmenu == 'Images'}class="active"{/if}><a href="/Images/">Галерея</a></li>
                    <li {if $mainmenu == 'User'}class="active"{/if}><a href="/User/Profile">Мой профиль</a></li>
                </ul>
                <ul class="nav pull-right">
                    <li><a href="/User/Logout"><i class="icon-off"></i> Выход</a></li>
                </ul>
            </div>
        </div>
    </div>
    <p class="navbar-text">
        {include "Blocks/Breadcrumbs.tpl"}
    </p>
</div>
<div class="clearfix"></div>

<div class="validate-errors popup">
    <h2 class="title"></h2>
    <div class="popup-close">[x]</div>
    <div class="error-text"></div>
</div>