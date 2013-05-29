{if $error}
    <div class="error_message">{$error}</div>
{elseif $result}
    <div class="success">{$result}</div>
{/if}
<div>
    <h1>Восстановление пароля, введите код присланный вам на почту</h1>
    <form id="login-form" action="/User/Reset" method="POST" class="validate">
        <input type="text" name="code" placeholder="Ваш код для сбоса пароля" class="validate-required"/>
        <input type="submit" value="Ввести"/>
    </form>
</div>
