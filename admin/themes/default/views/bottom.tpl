{if isset($bottom_gap['modules']) && !empty($bottom_gap['modules'])}
{foreach $bottom_gap['modules'] as $module}
{$module}
{/foreach}
{/if}
{if isset($bottom_gap['blocks']) && !empty($bottom_gap['blocks'])}
{foreach $bottom_gap['blocks'] as $block}
{$block}
{/foreach}
{/if}