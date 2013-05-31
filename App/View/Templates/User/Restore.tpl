{if $error}
    <div class="error_message">{$error}</div>
{elseif $result}
    <div class="success">{$result}</div>
{/if}
<div>
    <h4>Восстановление пароля, введите ваш е-мейл указанный при регистрации</h4>
    <form id="login-form" action="/User/Restore" method="POST" class="form-inline validate">
        <input type="text" name="email" placeholder="Ваш е-мейл регистрации" class="validate-required"/>
        <input type="submit" class="btn btn-primary" value="Восстановить"/>
    </form>
</div>