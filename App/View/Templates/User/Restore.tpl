{if $error}
    <div class="error_message">{$error}</div>
{elseif $result}
    <div class="success">{$result}</div>
{/if}
<div>
    <h1>Восстановление пароля, введите ваш е-мейл указанный при регистрации</h1>
    <form id="login-form" action="/User/Restore" method="POST" class="validate">
        <input type="text" name="email" placeholder="Ваш е-мейл регистрации" class="validate-required"/>
        <input type="submit" value="Восстановить"/>
    </form>
</div>
