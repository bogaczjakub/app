<title>{if isset($page_details[0].head_title) && !empty($page_details[0].head_title)}{$page_details[0].head_title}{/if}</title>
<meta name="description" content="{if isset($page_details[0].page_description) && !empty($page_details[0].page_description)}{$page_details[0].page_description}{/if}">
<meta name="keywords" content="{if isset($page_details[0].page_keywords) && !empty($page_details[0].page_keywords)}{$page_details[0].page_keywords}{/if}">
<meta name="author" content="{if isset($global_page_details.site_author) && !empty($global_page_details.site_author)}{$global_page_details.site_author}{/if}">
<meta http-equiv="refresh" content="">
<meta name="viewport" content="">
<base href="{if isset($global_page_details.base_url) && !empty($global_page_details.base_url)}{$global_page_details.base_url}{/if}" target="_blank">
{if isset($head_links && !empty($head_links))}
    {foreach from=$head_links key=key item=item}
        {if $key == 'css'}
            {foreach from=$item item=link}
                <link rel="stylesheet" type="text/css" href="{$link}">
            {/foreach}
        {elseif $key == 'js'}
            {foreach from=$item item=link}
                <script src="{$link}" type="text/javascript"></script>
            {/foreach}
        {/if}
    {/foreach}
{/if}
