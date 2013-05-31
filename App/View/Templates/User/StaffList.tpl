<script type="text/javascript">
    $().ready(function() {
    });
</script>
<div>
    <div class="title">
        <h3>Персонал</h3>
    </div>

    {if $_user.flag_admin}
        <p><a href="javascript: $('#frmCreate').removeClass('hidden'); $('#frmCreate input[name=login]').focus()" class="btn btn-primary">Добавить</a></p>
        <form id="frmCreate" class="well hidden validate" action="/Staff/Add">
            <legend>Добавить пользователя</legend>
            <div><input type="text" name="login" class="input-xxlarge validate-required" placeholder="Логин нового пользователя"/></div>
            <span class="help-block">Пароль созданного пользователя будет совпадать с его логином</span>
            <div><input type="submit" class="btn btn-primary" value="Создать"/></div>
        </form>
    {/if}

    <div class="band">
        <table class="table table-hover">
            <tr>
                <th></th>
                <th>Имя</th>
                <th>Отдел</th>
                <th>Инфо</th>
                <th>E-mail</th>
                <th>Телефон</th>
                <th>Внутренний номер</th>
                <th>Skype</th>
                <th>Статус</th>
            </tr>

            {if !empty($result.items)}
                {foreach from=$result.items item=row}
                    <tr onclick="document.location = '/Staff/View/{$row.id}'">
                        <td><img src="/Images/View/{$row.avatar_id}/80/80"/></td>
                        <td>{$row.fullname|display}</td>
                        <td>{$row.department|display}</td>
                        <td>{$row.about|display}</td>
                        <td>
                            {if !empty($row.contacts['Email'])}
                                {foreach from=$row.contacts['Email'] item=item}
                                    <p {if $item.comment}title="{$item.comment}"{/if}>
                                        <a href="mailto: {$item.value|display}">{$item.value|display}</a>
                                    </p>
                                {/foreach}
                            {/if}
                        </td>
                        <td>
                            {if !empty($row.contacts['Phone'])}
                                {foreach from=$row.contacts['Phone'] item=item}
                                    <p {if $item.comment}title="{$item.comment|display}"{/if}>
                                        <nobr>{$item.value|display}</nobr>
                                    </p>
                                {/foreach}
                            {/if}
                        </td>
                        <td>
                            {if !empty($row.contacts['Internal'])}
                                {foreach from=$row.contacts['Internal'] item=item}
                                    <p {if $item.comment}title="{$item.comment|display}"{/if}>
                                        {$item.value|display}
                                    </p>
                                {/foreach}
                            {/if}
                        </td>
                        <td>
                            {if !empty($row.contacts['Skype'])}
                                {foreach from=$row.contacts['Skype'] item=item}
                                    <p {if $item.comment}title="{$item.comment|display}"{/if}>
                                        <a href="skype: {$item.value|display}">{$item.value|display}</a>
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