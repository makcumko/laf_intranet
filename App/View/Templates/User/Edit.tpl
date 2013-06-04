<script type="text/javascript">
    function addContact() {
        var type = $("#ddlContactType").val();
        if ($("#dvContacts .contact-"+type).size() > 0) {
            // add field there
            var html = '<p>'+
                    ' <input type="text" name="contact_'+type+'_value[]" value="" class="validate-required" placeholder="'+type+'"/>'+
                    ' <input type="text" name="contact_'+type+'_comment[]" value="" class="input-xxlarge" placeholder="Комментарий"/>'+
                    ' <a href="javascript: void(0)" onclick="delContact(this)"><i class="icon-remove"></i></a>'+
                    '</p>';
            $("#dvContacts .contact-"+type+" .controls").append(html);
        } else {
            // create new
            var html = '<div class="control-group contact-'+type+'">'+
                    '<label class="control-label">'+type+'</label>'+
                    '<div class="controls">'+
                    '<p>'+
                    ' <input type="text" name="contact_'+type+'_value[]" value="" class="" placeholder="'+type+'"/>'+
                    ' <input type="text" name="contact_'+type+'_comment[]" value="" class="input-xxlarge" placeholder="Комментарий"/>'+
                    ' <a href="javascript: void(0)" onclick="delContact(this)"><i class="icon-remove"></i></a>'+
                    '</p>'+
                    '</div>'+
                    '</div>'
            $("#dvContacts").append(html);
        }
    }

    function delContact(control) {
        var p = $(control).parent();
        var container = p.parents(".control-group");
        console.log(container.html());
        p.remove();
        if (container.find("p").size() == 0) {
            container.remove();
        }
    }

    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e)  {
                $('#imgAvatar').attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    $().ready(function() {
    });
</script>

<h3>{$result.fullname}</h3>
<form enctype="multipart/form-data" action="/Staff/Edit/{$result.id}" method="post" class="form-horizontal validate" >
<div>
    <div class="span2">
        <label for="avatar">
            <img id="imgAvatar" src="/Images/View/{$result.avatar_id}/200/200" style="max-width: 250px; max-height: 500px; overflow: hidden;"/>
            <br/>
            Нажмите чтобы изменить
        </label>
        <input type="file" id="avatar" name="avatar" class="hidden" onchange="readURL(this);"/>
    </div>
    <div class="span10">
        <div class="control-group">
            <label class="control-label" for="fullname">Полное имя</label>
            <div class="controls">
                <input type="text" id="fullname" name="fullname" value="{$result.fullname}" class="validate-required" placeholder="Иванов Иван Иванович" title="Полное имя"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="shortname">Краткое имя</label>
            <div class="controls">
                <input type="text" id="shortname" name="shortname" value="{$result.shortname}" class="validate-required" placeholder="Иван Иванов" title="Краткое имя"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="department_id">Отдел</label>
            <div class="controls">
                <select name="department_id">
                    <option value="">- Отдел не выбран</option>
                    {foreach from=$departments item=dept}
                        <option value="{$dept.id}" {if $result.department_id == $dept.id}selected="selected"{/if}>{$dept.name}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="login">Логин</label>
            <div class="controls">
                <input type="text" id="login" name="login" value="{$result.login}" class="validate-required" placeholder="login@laf24.ru" title="Логин"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="password">Новый пароль</label>
            <div class="controls">
                <input type="text" id="password" name="password" value="" placeholder="******" title="Новый пароль"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="password2">Повтор пароля</label>
            <div class="controls">
                <input type="text" id="password2" name="password2" value="" placeholder="******" title="Повтор пароля"/>
            </div>
        </div>
        <div class="control-group">
            <label class="control-label" for="about">О себе</label>
            <div class="controls">
                <textarea id="about" name="about" class="input-block-level" placeholder="Краткая информация, должность">{$result.about}</textarea>
            </div>
        </div>

        <div class="control-group">
            <h4>Контактные данные пользователя</h4>
        </div>

        <div id="dvContacts">
            {foreach from=$result.contacts key=type item=contact}
                <div class="control-group contact-{$type}">
                    <label class="control-label" for="about">{$contactTypes[$type]['name']}</label>
                    <div class="controls">
                        {foreach from=$contact item=item}
                            <p>
                                <input type="text" name="contact_{$type}_value[]" value="{$item.value}" {if $contactTypes[$type]['required']}class="validate-required"{/if} placeholder="{$contactTypes[$type]['placeholder']}"/>
                                <input type="text" name="contact_{$type}_comment[]" value="{$item.comment}" class="input-xxlarge" placeholder="Комментарий"/>
                                <a href="javascript: void(0)" onclick="delContact(this)"><i class="icon-remove"></i></a>
                            </p>
                        {/foreach}
                    </div>
                </div>
            {/foreach}

            {* some obligatory fields *}
            {foreach from=$contactTypes key=type item=ct}
                {if $ct.required && empty($result.contacts[$type])}
                    <div class="control-group contact-{$type}">
                        <label class="control-label">{$ct.name}</label>
                        <div class="controls">
                            <p>
                                <input type="text" name="contact_{$type}_value[]" value="" {if $ct.required}class="validate-required"{/if} placeholder="{$ct.placeholder}" title="{$ct.name}"/>
                                <input type="text" name="contact_{$type}_comment[]" value="" class="input-xxlarge" placeholder="Комментарий"/>
                                <a href="javascript: void(0)" onclick="delContact(this)"><i class="icon-remove"></i></a>
                            </p>
                        </div>
                    </div>
                {/if}
            {/foreach}

            {*
            {if empty($result.contacts.Email)}
                <div class="control-group contact-Email">
                    <label class="control-label">Email</label>
                    <div class="controls">
                        <p>
                            <input type="text" name="contact_Email_value[]" value="" class="validate-required" placeholder="vasya@pupkin.ru" title="Email"/>
                            <input type="text" name="contact_Email_comment[]" value="" class="input-xxlarge" placeholder="Комментарий"/>
                            <a href="javascript: void(0)" onclick="delContact(this)"><i class="icon-remove"></i></a>
                        </p>
                    </div>
                </div>
            {/if}
            {if empty($result.contacts.Phone)}
                <div class="control-group contact-Phone">
                    <label class="control-label">Phone</label>
                    <div class="controls">
                        <p>
                            <input type="text" name="contact_Phone_value[]" value="" class="validate-required" placeholder="+7-123-4567890" title="Телефон"/>
                            <input type="text" name="contact_Phone_comment[]" value="" class="input-xxlarge" placeholder="Комментарий"/>
                            <a href="javascript: void(0)" onclick="delContact(this)"><i class="icon-remove"></i></a>
                        </p>
                    </div>
                </div>
            {/if}
            {if empty($result.contacts.Internal)}
                <div class="control-group contact-Internal">
                    <label class="control-label">Internal</label>
                    <div class="controls">
                        <p>
                            <input type="text" name="contact_Internal_value[]" value="" class="validate-required" placeholder="1234" title="Внутренний номер"/>
                            <input type="text" name="contact_Internal_comment[]" value="" class="input-xxlarge" placeholder="Комментарий"/>
                            <a href="javascript: void(0)" onclick="delContact(this)"><i class="icon-remove"></i></a>
                        </p>
                    </div>
                </div>
            {/if}
            {if empty($result.contacts.Skype)}
                <div class="control-group contact-Skype">
                    <label class="control-label">Skype</label>
                    <div class="controls">
                        <p>
                            <input type="text" name="contact_Skype_value[]" value="" class="validate-required" placeholder="vasya.pupkin" title="Skype"/>
                            <input type="text" name="contact_Skype_comment[]" value="" class="input-xxlarge" placeholder="Комментарий"/>
                            <a href="javascript: void(0)" onclick="delContact(this)"><i class="icon-remove"></i></a>
                        </p>
                    </div>
                </div>
            {/if}
            *}
        </div>
        <div class="control-group">
            <label class="control-label">Добавить контакт</label>
            <div class="controls">
                <select id="ddlContactType">
                    {foreach from=$contactTypes key=type item=ct}
                        <option value="{$type}">{$ct.name}</option>
                    {/foreach}
                </select>
                <a href="javascript: addContact()" class="btn btn-primary">Добавить</a>
            </div>
        </div>

        <div class="control-group contact-phone">
            <div class="controls">
                <input type="submit" class="btn btn-primary" value="Сохранить"/>
                <a href="/Staff/View/{$result.id}" class="btn">Отменить</a>
            </div>
        </div>
    </div>
    <div class="clearfix"></div>

</div>
</form>