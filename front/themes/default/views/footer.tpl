{if isset($footer_gap['modules']) && !empty($footer_gap['modules'])}
{foreach $footer_gap['modules'] as $module}
{$module}
{/foreach}
{/if}
{if isset($footer_gap['blocks']) && !empty($footer_gap['blocks'])}
{foreach $footer_gap['blocks'] as $block}
{$block}
{/foreach}
{/if}