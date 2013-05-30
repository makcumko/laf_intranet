<div class="pagination">
    <ul>
        {if $data.currentPage > 1}
            <li><a href="{$url}{$data.currentPage-1}">&laquo;</a></li>
        {else}
            <li class="disabled"><a href="{$url}{$data.currentPage}">&laquo;</a></li>
        {/if}
        {for $page = 1 to $data.pages}
            <li {if $page == $data.currentPage}class="active"{/if}><a href="{$url}{$page}">{$page}</a></li>
        {/for}
        {if $data.currentPage < $data.pages}
            <li><a href="{$url}{$data.currentPage+1}">&raquo;</a></li>
        {else}
            <li class="disabled"><a href="{$url}{$data.currentPage}">&raquo;</a></li>
        {/if}
    </ul>
</div>