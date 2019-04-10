<!DOCTYPE html>
<html lang="{$global_details.site_language}">

<head>
    {include file="head.tpl"}
</head>

<body id="{$page_details[0].pages_controller}">
    <div id="page-container">
        {if isset($pages_gaps_allowed.header_gap)}
        <div id="header-section">
            {include file="header.tpl"}
        </div>
        {/if}
        <div id="content-section">
            <div id="content-container" class="container-fluid">
                {if isset($pages_gaps_allowed.top_gap)}
                <div id="top" class="row">
                    <div class="col-xs-12">
                        {include file="top.tpl"}
                    </div>
                </div>
                {/if}
                <div id="content-row" class="row">
                    {if $page_details[0].pages_column_structure == 'left_column' ||
                    $page_details[0].pages_column_structure ==
                    'full_column'}
                    {if isset($pages_gaps_allowed.left_column_gap)}
                    <div id="left-column" class="col-xs-12 col-sm-3 col-lg-2">
                        {if isset($left_column_gap) && !empty($left_column_gap)}
                        {foreach $left_column_gap as $key => $value}
                        {$value}
                        {/foreach}
                        {/if}
                    </div>
                    {/if}
                    {/if}
                    {if isset($pages_gaps_allowed.center_column_gap)}
                    <div id="center-column"
                        class="col-xs-12 {if $page_details[0].pages_column_structure == 'full_width'}col-sm-12{elseif $page_details[0].pages_column_structure == 'left_column' || $page_details[0].pages_column_structure == 'right_column'}col-sm-9 col-lg-10{/if}">
                        {include file="alerts.tpl"}
                        <div class="page-header">
                            <h1 class="page-name">
                                {if (isset($page_details[0].pages_head_title) &&
                                !empty($page_details[0].pages_head_title))}
                                {$page_details[0].pages_head_title}
                                {/if}
                            </h1>
                            <p class="page-description">
                                {if (isset($page_details[0].pages_description) &&
                                !empty($page_details[0].pages_description))}
                                {$page_details[0].pages_description}
                                {/if}
                            </p>
                        </div>
                        {if isset($center_column_gap) && !empty($center_column_gap)}
                        {foreach $center_column_gap as $key => $value}
                        {$value}
                        {/foreach}
                        {/if}
                        {include file="$content"}
                    </div>
                    {/if}
                    {if $page_details[0].pages_column_structure == 'right_column' ||
                    $page_details[0].pages_column_structure ==
                    'full_column'}
                    {if isset($pages_gaps_allowed.right_column_gap)}
                    <div id="right-column" class="col-xs-12 col-sm-4">
                        {if isset($right_column_gap) && !empty($right_column_gap)}
                        {foreach $right_column_gap as $key => $value}
                        {$value}
                        {/foreach}
                        {/if}
                    </div>
                    {/if}
                    {/if}
                </div>
                {if isset($pages_gaps_allowed.bottom_gap)}
                <div id="bottom" class="row">
                    <div class="col-xs-12">
                        {include file="bottom.tpl"}
                    </div>
                </div>
                {/if}
            </div>
        </div>
        {if isset($pages_gaps_allowed.footer_gap)}
        <div id="footer-section">
            <div id="footer-container" class="container-fluid">
                <div id="footer" class="row">
                    <div class="col-xs-12">
                        {include file="footer.tpl"}
                    </div>
                </div>
            </div>
        </div>
        {/if}
    </div>
</body>

</html>