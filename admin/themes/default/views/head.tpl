<title>{if isset($page_details[0].head_title) && !empty($page_details[0].head_title)}{$page_details[0].head_title}{/if}
</title>
<meta name="description"
    content="{if isset($page_details[0].description) && !empty($page_details[0].description)}{$page_details[0].description}{/if}">
<meta name="keywords"
    content="{if isset($page_details[0].keywords) && !empty($page_details[0].keywords)}{$page_details[0].keywords}{/if}">
<meta name="author"
    content="{if isset($global_details.site_author) && !empty($global_details.site_author)}{$global_details.site_author}{/if}">
<meta http-equiv="refresh" content="">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="{if isset($global_details.base_url) && !empty($global_details.base_url)}{$global_details.base_url}{/if}"
    target="_blank">
{if isset($head_links && !empty($head_links))}
{foreach $head_links as $key => $value}
{if $key == 'css'}
{foreach $value as $link}
<link rel="stylesheet" type="text/css" href="{$link}">
{/foreach}
{elseif $key == 'js'}
{foreach $value as $link}
<script src="{$link}" type="text/javascript"></script>
{/foreach}
{/if}
{/foreach}
{/if}
{if isset($head_gap['modules']) && !empty($head_gap['modules'])}
{foreach $head_gap['modules'] as $module}
{$module}
{/foreach}
{/if}
{if isset($head_gap['blocks']) && !empty($head_gap['blocks'])}
{foreach $head_gap['blocks'] as $block}
{$block}
{/foreach}
{/if}