<script type="text/javascript">
    function addComment(form) {
        var text = $(form).find("input[name=text]").val();
        if (text.length == 0) return;

        $.ajax({
            url: "/Images/Comments/{$result.id}/Add",
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
</script>

<div>
    <div class="title">
        <h3>
            Галерея
        </h3>
    </div>

    <div class="well imageinfo">
        <div class="well pull-left">
            <img src="/Images/View/{$result.id}"/>
        </div>

        <div class="well pull-left">
            <div class="date">Добавлено: {$result.ctime|date_nice_format:"d M Y H:i"}</div>
            <div class="author">
                Автор: <a href="/Staff/View/{$result.author_id}">{$result.author|display}</a>
                <a href="/Images/Main/1/0/User={$result.author_id}">(посмотреть все его изображения)</a>
            </div>
            <div class="description">Описание{$result.description|display|parse_tags:"/Images/Main/1/0/Tag=#"}</div>
        </div>

        <div class="clearfix"></div>

        <div class="comments well">
            <form method="post" class="form-inline" onsubmit="addComment(this); return false;">
                <input type="text" name="text" placeholder="Ваш комментарий.." class="input-xxlarge"/>
                <input type="submit" value="Отправить" class="btn btn-primary"/>
            </form>

            <div class="rows">
                {include "Blocks/Comments.tpl" result=$comments}
            </div>
        </div>

    </div>


    <div class="clearfix"></div>

</div>