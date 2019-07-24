<div id="breadcrumb">
    <ol class="breadcrumb">
        {if isset($breadcrumbs) && !empty($breadcrumbs)}
        {foreach from=$breadcrumbs key=$key item=$breadcrumb name=breadcrumbs}
        {if $smarty.foreach.breadcrumbs.last}
        <li class="active">{$breadcrumb.category_name}</li>
        {else}
        <li><a target="_self" href="{$breadcrumb.category_uri}">{$breadcrumb.category_name}</a></li>
        {/if}
        {/foreach}
        {/if}
    </ol>
</div>
{if isset($top_gap['modules']) && !empty($top_gap['modules'])}
{foreach $top_gap['modules'] as $module}
{$module}
{/foreach}
{/if}
{if isset($top_gap['blocks']) && !empty($top_gap['blocks'])}
{foreach $top_gap['blocks'] as $block}
{$block}
{/foreach}
{/if}