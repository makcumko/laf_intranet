<div class="pagination">
    <ul>
        {if $data.currentPage > 1}
            <li><a href="{$url}{$data.currentPage-1}{if isset($order)}/{$order}{/if}{if isset($filter)}/{$filter}{/if}">&laquo;</a></li>
        {else}
            <li class="disabled"><a href="#">&laquo;</a></li>
        {/if}
        {for $page = 1 to $data.pages}
            <li {if $page == $data.currentPage}class="active"{/if}><a href="{$url}{$page}{if isset($order)}/{$order}{/if}{if isset($filter)}/{$filter}{/if}">{$page}</a></li>
        {/for}
        {if $data.currentPage < $data.pages}
            <li><a href="{$url}{$data.currentPage+1}{if isset($order)}/{$order}{/if}{if isset($filter)}/{$filter}{/if}">&raquo;</a></li>
        {else}
            <li class="disabled"><a href="#">&raquo;</a></li>
        {/if}
    </ul>
</div>