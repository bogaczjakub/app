{if isset($bottom_gap) && !empty($bottom_gap)}
{foreach $bottom_gap as $type}
{foreach $type as $content}
{$content}
{/foreach}
{/foreach}
{/if}