{if !empty($errors)}
    {foreach from=$errors item=error}
        <div class='alert alert-error'>
            <button type='button' class='close' data-dismiss='alert'>x</button>
            {$error.text}
        </div>
    {/foreach}
{/if}
{if !empty($notifications)}

    {foreach from=$notifications item=note}
    <div class='alert alert-block'>
        <button type='button' class='close' data-dismiss='alert'>x</button>
        {$note.text}
    </div>
    {/foreach}
{/if}