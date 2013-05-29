<form method="post" action="/User/Register">
    <legend>Заявка на регистрацию</legend>


    <label>Логин</label>
    <input type="text" name="login" placeholder="user@server.com" value="{$form.login}">

    <label>Имя</label>
    <input type="text" name="name" placeholder="Иванов Иван Иванович" value="{$form.name}">

    <label>Пароль</label>
    <input type="password" name="password" placeholder="********">

    <label>Повторить пароль</label>
    <input type="password" name="password2" placeholder="********">

    <hr>
    <input type="submit" class="btn btn-small btn-primary" value="Отправить заявку">
</form>