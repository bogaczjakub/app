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
{if isset($top_gap) && !empty($top_gap)}
{foreach $top_gap as $type}
{foreach $type as $content}
{$content}
{/foreach}
{/foreach}
{/if}