<script type="text/javascript">
    tinymce.init({
        selector: "textarea",
        theme: "modern",
        plugins: [
            "advlist autolink lists link image charmap print preview hr anchor pagebreak",
            "searchreplace wordcount visualblocks visualchars code fullscreen",
            "insertdatetime media nonbreaking save table contextmenu directionality",
            "emoticons template paste"
        ],
        toolbar1: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
        toolbar2: "print preview media | forecolor backcolor emoticons",
        templates: [
            { title: 'Test template 1', content: 'Test 1' },
            { title: 'Test template 2', content: 'Test 2' }
        ]
    });
</script>

<form method="post">
    <input type="hidden" name="id" value="{$result.id}"/>
    <table>
        <tr>
            <td>Код страницы</td>
            <td><input type="text" name="code" value="{$result.code}"/></td>
        </tr>
        <tr>
            <td>Заголовок страницы</td>
            <td><input type="text" name="title" value="{$result.title}"/></td>
        </tr>
        <tr>
            <td>Текст страницы</td>
            <td><textarea rows="20" cols="60" id="text" name="text" class="editor">{$result.text}</textarea></td>
        </tr>
    </table>
    <input type="submit" value="Сохранить"/>
</form>
