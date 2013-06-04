<h3>{$result.fullname} {if $_user.flag_admin}<a href="/Staff/Edit/{$result.id}" class="btn">Редактировать</a>{/if}</h3>
<div>
    <div class="span2">
        <img src="/Images/View/{$result.avatar_id}/200/200"/>
    </div>
    <div class="span10">
        <table class="table">
            <tr>
                <th class="span4">Полное имя</th>
                <td>{$result.fullname}</td>
            </tr>
            <tr>
                <th>Краткое имя</th>
                <td>{$result.shortname}</td>
            </tr>
            <tr>
                <th>Последнее действие</th>
                <td>{$result.last_action|date_nice_format:"d M Y H:i"}</td>
            </tr>
            <tr>
                <th>Отдел</th>
                <td>{$result.department}</td>
            </tr>
            <tr>
                <th>О себе</th>
                <td>{$result.about}</td>
            </tr>
            <tr>
                <th>Статус</th>
                <td>
                    {if !$result.flag_approved}
                        {if $_user.flag_admin}
                            <a href="/Staff/Approve/{$result.id}">Подтвердить</a>
                        {else}
                            Ожидает подтверждения
                        {/if}
                    {else}
                        Подтвержден
                    {/if}
                </td>
            </tr>

            <tr>
                <td colspan="2">
                    <h4>Контактные данные пользователя</h4>
                </td>
            </tr>

            {foreach from=$result.contacts key=type item=contact}
                <tr>
                    <th>{$contactTypes[$type]['name']}</th>
                    <td>
                        {foreach from=$contact item=item}
                            <div>
                                {$item.value}
                                {if $item.comment} ({$item.comment}){/if}
                            </div>
                        {/foreach}
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>

</div>