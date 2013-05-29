{if !empty($result)}
    <table>
        <tr>
            <th>id</th>
            <th>код</th>
            <th>заголовок</th>
            <th></th>
        </tr>
        {foreach from=$result item=row}
            <tr>
                <td>{$row.id}</td>
                <td>{$row.code}</td>
                <td>{$row.title}</td>
                <td><a href="/Admin/PageEdit/{$row.id}">Редактировать</a></td>
            </tr>
        {/foreach}
    </table>
{else}
    Нет страниц
{/if}

<br/>
<a href="/Admin/PageEdit/">Создать новую страницу</a>