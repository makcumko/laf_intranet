<script type="text/javascript">
    function toggleComments(btn) {
        var commentsDiv = $(btn).parent().find('.comments');
        var id = $(btn).parent().attr("data-id");

        if (commentsDiv.hasClass("hidden")) {
            commentsDiv.removeClass("hidden");
            commentsDiv.find(".rows").html("<img src='/img/ajax-loader.gif'/>");
            $.ajax({
                url: "/Idea/Comments/",
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
            url: "/Idea/Comments/"+id+"/Add",
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
        <h3>Последние предложения</h3>
    </div>

    <p><a href="javascript: $('#frmCreate').removeClass('hidden'); $('#frmCreate input[name=title]').focus()" class="btn btn-primary">Добавить</a></p>
    <form id="frmCreate" class="well hidden validate" action="/Idea/Add">
        <legend>Добавить идею или предложение</legend>
        <div><input type="text" name="title" class="input-xxlarge validate-required" placeholder="Заголовок идеи"/></div>
        <div><textarea name="text" class="input-block-level validate-required" placeholder="Текст предложения"></textarea></div>
        <span class="help-block">Все пользователи системы смогут увидеть ваше предложение и проголосовать за него </span>
        <div><input type="submit" class="btn btn-primary" value="Опубликовать"/></div>
    </form>

    <div class="band">
    {if !empty($result.items)}
        {foreach from=$result.items item=row}
            <div class="news well" id="idea_{$row.id}" data-id="{$row.id}">
                <p>
                    {if $row.title}
                        <b>{$row.title}</b>
                        <span> от <a href="/Staff/View/{$row.author_id}">{$row.author}</a> {$row.ctime|date_nice_format:"d M Y H:i"}</span>
                    {else}
                        Предложение от <a href="/Staff/View/{$row.author_id}">{$row.author}</a> {$row.ctime|date_nice_format:"d M Y H:i"}
                    {/if}
                </p>

                <blockquote>{$row.text}</blockquote>


                <div class="span1">
                <div class="btn-group">
                    <a class="btn btn-success btn-mini" href="/Idea/Vote/{$row['id']}/1">За</a>
                    <a class="btn btn-mini" href="/Idea/Vote/{$row['id']}/0">Против</a>
                </div>
                </div>
                <div class="span10">
                <div class="progress">
                    {if $row['votes']['yes'] > 0}
                        <div class="bar bar-success" style="width: {$row['votes']['yes'] / $row['votes']['total'] * 100}%;">{$row['votes']['yes']}</div>
                    {/if}
                    {if $row['votes']['no'] > 0}
                        <div class="bar bar-danger" style="width: {$row['votes']['no'] / $row['votes']['total'] * 100}%;">{$row['votes']['no']}</div>
                    {/if}
                    {if $row['votes']['total'] == 0}
                        <div class="bar bar-info" style="width: 100%;">Пока еще нет голосов</div>
                    {/if}
                </div>
                </div>
                <div class="clearfix"></div>

                <div class="">
                    <a href="javascript: void(0)" onclick="toggleComments(this)">Комментариев: {$row.comments|sizeof}</a>
                </div>



                <div class="comments hidden well">
                    <form method="post" class="form-inline" onsubmit="addComment(this); return false;">
                        <input type="text" name="text" placeholder="Ваш комментарий.." class="input-xxlarge"/>
                        <input type="submit" value="Отправить" class="btn btn-primary"/>
                    </form>

                    <div class="rows"></div>
                </div>
            </div>
        {/foreach}
        {include "Blocks/Pager.tpl" url="/Idea/Main/" data=$result}
    {else}
        Свежих идей пока нет
    {/if}
    </div>
</div>
