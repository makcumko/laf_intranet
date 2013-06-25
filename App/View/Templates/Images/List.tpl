<div>
    <div class="title">
        <h3>
            Галерея
            <a href="javascript: void(0)" onclick="$('#frmCreate').removeClass('hidden'); $('#frmCreate input[name=title]').focus();" class="btn btn-primary pull-right">Добавить</a>
        </h3>
    </div>

    <form method="post" action="/Images/Upload" enctype="multipart/form-data" id="frmCreate" class="well hidden validate">
        <legend>Добавить изображение</legend>
        <div><input type="file" name="image" class="input-xxlarge"/></div>
        <div><input type="text" name="description" class="input-xxlarge validate-required" placeholder="Описание картинки"/></div>
        <span class="help-block">Возможно использование хештегов, например #работа</span>
        <div><input type="submit" class="btn btn-primary" value="Загрузить"/></div>
    </form>

    <div class="well">
        {if !empty($result.items)}
            {foreach from=$result.items item=row}
                <div class="well gallery-item">
                    <a href="/Images/Info/{$row.id}">
                        <img src="/Images/View/{$row.id}/200/200" class="img-polaroid" alt=""/>
                    </a>
                    <div class="date">{$row.ctime|date_nice_format}</div>
                    <div class="author">
                        <a href="/Staff/View/{$row.author_id}">{$row.author}</a>
                        <a href="/Images/Main/1/0/User={$row.author_id}">(все)</a>
                    </div>
                    <div class="description">{$row.description|parse_tags:"/Images/Main/1/0/Tag=#"}</div>
                </div>
            {/foreach}

            <div class="clearfix"></div>

            {include "Blocks/Pager.tpl" url="/Images/Main/" data=$result}
        {else}
            Нет изображений
        {/if}
    </div>

</div>