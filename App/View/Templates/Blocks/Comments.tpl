{if !empty($result)}
    {foreach from=$result item=comment}
        <p><a href="/Staff/View/{$comment.author_id}">{$comment.author}</a> {$comment.ctime|date_nice_format:"H:i d M Y"}</p>
        <blockquote>{$comment.text}</blockquote>
    {/foreach}
{/if}
