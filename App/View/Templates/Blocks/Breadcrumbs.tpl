<ul class="breadcrumb well">
    <li><a href="/">Главная</a> <span class="divider">/</span></li>
    {foreach from=$_breadcrumbs item=crumb}
        <li><a href="{$crumb.href}">{$crumb.title}</a> <span class="divider">/</span></li>
    {/foreach}
</ul>
