{if isset($page_bottom_gap['modules']) && !empty($page_bottom_gap['modules'])}
{foreach $page_bottom_gap['modules'] as $module}
{$module}
{/foreach}
{/if}
{if isset($page_bottom_gap['blocks']) && !empty($page_bottom_gap['blocks'])}
{foreach $page_bottom_gap['blocks'] as $block}
{$block}
{/foreach}
{/if}