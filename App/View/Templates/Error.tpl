<div class="error">
    <h1>Bad things happen</h1>
    {*{if !empty($errors)}*}
        {*{foreach from=$errors item=error}*}
            {*<div class='alert alert-error'>*}
                {*<button type='button' class='close' data-dismiss='alert'>x</button>*}
                {*{$error.text}*}
            {*</div>*}
        {*{/foreach}*}
    {*{/if}*}
</div>
