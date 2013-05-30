<script type="text/javascript">
    $().ready(function() {
    });
</script>
<div>
    <div class="title">
        <h3>Персонал</h3>
    </div>
    <div class="band">
        <table class="table table-hover">
            <tr>
                <th></th>
                <th>Имя</th>
                <th>Отдел</th>
                <th>Инфо</th>
                <th>E-mail</th>
                <th>Телефон</th>
                <th>Skype</th>
                <th>Статус</th>
            </tr>

            {if !empty($result.items)}
                {foreach from=$result.items item=row}
                    <tr onclick="document.location = '/Staff/View/{$row.id}'">
                        <td><img src="/Images/Get/{$row.avatar_id}/80/80"/></td>
                        <td>{$row.fullname}</td>
                        <td>{$row.department}</td>
                        <td>{$row.about}</td>
                        <td>
                            {if !empty($row.contacts['email'])}
                                {foreach from=$row.contacts['email'] item=item}
                                    <p>
                                        <a href="mailto: {$item.value}">{$item.value}</a>
                                        {if $item.comment} ({$item.comment}){/if}
                                    </p>
                                {/foreach}
                            {/if}
                        </td>
                        <td>
                            {if !empty($row.contacts['phone'])}
                                {foreach from=$row.contacts['phone'] item=item}
                                    <p>
                                        {$item.value}
                                        {if $item.comment} ({$item.comment}){/if}
                                    </p>
                                {/foreach}
                            {/if}
                        </td>
                        <td>
                            {if !empty($row.contacts['skype'])}
                                {foreach from=$row.contacts['skype'] item=item}
                                    <p>
                                        <a href="skype: {$item.value}">{$item.value}</a>
                                        {if $item.comment} ({$item.comment}){/if}
                                    </p>
                                {/foreach}
                            {/if}
                        </td>
                        <td>
                            {if !$row.flag_approved}
                                {if $_user.flag_admin}
                                    <a href="/Staff/Approve/{$row.id}">Подтвердить</a>
                                {else}
                                    Ожидает подтверждения
                                {/if}
                            {else}
                                Подтвержден
                            {/if}
                        </td>
                    </tr>
                {/foreach}
            {/if}
        </table>
        {include "Blocks/Pager.tpl" url="/Staff/Main/" data=$result}

    </div>
</div>