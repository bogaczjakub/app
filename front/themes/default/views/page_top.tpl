{if isset($page_top_gap['modules']) && !empty($page_top_gap['modules'])}
{foreach $page_top_gap['modules'] as $module}
{$module}
{/foreach}
{/if}
{if isset($page_top_gap['blocks']) && !empty($page_top_gap['blocks'])}
{foreach $page_top_gap['blocks'] as $block}
{$block}
{/foreach}
{/if}