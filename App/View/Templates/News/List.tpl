<script type="text/javascript">
    function toggleComments(btn) {
        var commentsDiv = $(btn).parent().find('.comments');
        var id = $(btn).parent().attr("data-id");

        if (commentsDiv.hasClass("hidden")) {
            commentsDiv.removeClass("hidden");
            commentsDiv.find(".rows").html("<img src='/img/ajax-loader.gif'/>");
            $.ajax({
                url: "/News/Comments/",
                cache: false,
                data: { "id": id },
                success: function(response) {
                    commentsDiv.find(".rows").html(response);
                    commentsDiv.find("input[name=text]").focus();
                }
            });
        } else {
            commentsDiv.addClass("hidden");
        }
    }

    function addComment(form) {
        var id = $(form).parents(".news").attr("data-id");
        var text = $(form).find("input[name=text]").val();
        if (text.length == 0) return;

        $.ajax({
            url: "/News/Comments/"+id+"/Add",
            cache: false,
            data: {
                "text": text
            },
            success: function(response) {
                $(form).parent().find(".rows").html(response);
                $(form).find("input[name=text]").val("");
            }
        });
    }

    $().ready(function() {
    });
</script>
<div>
    <div class="title">
        <h3>Последние новости</h3>
        {if $_user.flag_admin}
            <a href="javascript: $('#frmCreate').removeClass('hidden'); $('#frmCreate input[name=title]').focus()" class="btn btn-primary pull-right">Добавить</a>
        {/if}
    </div>
    {if $_user.flag_admin}
        <form id="frmCreate" class="well hidden validate" action="/News/Add">
            <legend>Добавить новость</legend>
            <div><input type="text" name="title" class="input-xxlarge validate-required" placeholder="Заголовок новости"/></div>
            <div><textarea name="text" class="input-block-level validate-required" placeholder="Текст новости"></textarea></div>
            <div><input type="submit" class="btn btn-primary" value="Опубликовать"/></div>
        </form>
    {/if}
    <div class="band">
        {if !empty($result.items)}
            {foreach from=$result.items item=row}
                <div class="news well" id="news_{$row.id}" data-id="{$row.id}">
                    <p>
                        <b>{$row.title}</b>
                        <span> от <a href="/Staff/View/{$row.author_id}"><img src="/Images/View/{$row.author_avatar_id}/20/20"/> {$row.author|display}</a> {$row.ctime|date_nice_format:"d M Y H:i"}</span>
                    </p>

                    <blockquote>{$row.text|display}</blockquote>

                    <a href="javascript: void(0)" onclick="toggleComments(this)">Комментариев: {$row.comments|sizeof}</a>

                    <div class="comments hidden well">
                        <form method="post" class="form-inline" onsubmit="addComment(this); return false;">
                            <input type="text" name="text" placeholder="Ваш комментарий.." class="input-xxlarge"/>
                            <input type="submit" value="Отправить" class="btn btn-primary"/>
                        </form>

                        <div class="rows"></div>
                    </div>
                </div>
            {/foreach}
            {include "Blocks/Pager.tpl" url="/News/Main/" data=$result}
        {else}
            Свежих новостей пока нет
        {/if}
    </div>
</div>