<div>
    <div class="title">
        <h3>Документы</h3>
    </div>

    <div class="band">
        <table class="table">
            <tr>
                <th></th>
                <th>Файл</th>
                <th>Описание</th>
            </tr>

            {if !empty($result.items)}
                {foreach from=$result.items item=row}
                    <tr>
                        <td><a href="/upload/Documents/{$row.filename}"><i class="icon-download"></i></a></td>
                        <td><a href="/upload/Documents/{$row.filename}">{$row.filename}</a></td>
                        <td>{$row.description}</td>
                    </tr>
                {/foreach}
            {/if}
        </table>
        {include "Blocks/Pager.tpl" url="/Documents/Main/" data=$result}

    </div>
</div>