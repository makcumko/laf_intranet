<section class="auth-form">

    {if $error}<div class='error_message'>{$error}</div>{/if}

    <form id="login-form" action="/User/UpdateProfile" method="POST" class="validate">
        <table class="auth-table" style="width: 600px">
            <tr>
                <th>Имя, псевдоним</th>
                <td><input type="text" name="username" placeholder="Имя пользователя" value="{$result.name}"/></td>
                <td></td>
            </tr>
            <tr>
                <th>Мобильный телефон</th>
                <td>
                    +7
                    <input type="text" maxlength="3" name="phonecode" class='phone-code numeric validate-required validate-length_3_3' value='{$result.phonecode}' title="Код"/>
                    <input type="text" maxlength="7" name="phonenum" class='phone-num numeric validate-required validate-length_7_7' value='{$result.phonenum}' title="Телефон"/>
                </td>
                <td>
                    <input type="checkbox" name="flag_notify_sms" id="sms-notifies" disabled="disabled" {if $result.flag_notify_sms}checked="checked"{/if}/>
                    Присылать SMS уведомления
                </td>
            </tr>
            <tr>
                <th>E-mail</th>
                <td><input type="text" name="email" placeholder="E-mail" value="{$result.email}" autocomplete="off"/></td>
                <td>
                    <input type="checkbox" name="flag_notify_email" id="mail-notifies" {if $result.flag_notify_email}checked="checked"{/if}/>
                    Присылать уведомления по почте
                </td>
            </tr>
            <tr>
                <th>Пароль</th>
                <td>
                    <input type="password" name="password" placeholder="Новый пароль" autocomplete="off"/>
                </td>
                <td>
                    <input type="password" name="password2" placeholder="Повторный пароль" autocomplete="off"/>
                </td>
            </tr>
            <tr>
                <th colspan="2"> <input type="submit" class="button" value="Сохранить"/></th>
            </tr>
        </table>

    </form>
</section>

{*
 <section class="prefences-form">

    <form method="POST" action="/User/UpdateProfile">
        <h6 class='errText'></h6>
        <input type="text" name="username" id='user-name' value='{$result.name}'/>
        <input type="text" maxlength="3" name="phonecode" id='phone-code'  value='{$result.phonecode}' class="phone-code numeric"/>
        <input type="text" maxlength="7" name="phonenum" id='phone-number'  value='{$result.phonenum}' class="phone-num numeric"/>
        <input type="text" name="email" id='email' value='{$result.email}' />
        <input type="password" name="password" id='password' />
	<input type="checkbox" name="flag_notify_sms" id="sms-notifies" disabled="disabled" {if $result.flag_notify_sms}checked="checked"{/if}>
	<label for="sms-notifies" id="sms-label"></label>
	<input type="checkbox" name="flag_notify_email" id="mail-notifies" {if $result.flag_notify_email}checked="checked"{/if}>
	<label for="mail-notifies" id="mail-label"></label>
        <div class="error"><em> </em></div>
	<button class="button" onClick="this.parentNode.submit(); return false;">сохранить изменения</button>
    </form>
</section>
*}