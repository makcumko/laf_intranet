{if !empty($result)}
    {foreach from=$result item=comment}
        <p><a href="/Staff/View/{$comment.author_id}"><img src="/Images/View/{$comment.author_avatar_id}/20/20"/> {$comment.author|display}</a> {$comment.ctime|date_nice_format:"H:i d M Y"}</p>
        <blockquote>{$comment.text|display}</blockquote>
    {/foreach}
{/if}
