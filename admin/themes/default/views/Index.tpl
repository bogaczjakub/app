<!DOCTYPE html>
<html lang="{$global_page_details.site_language}">

<head>
    {include file="head.tpl" title="Page head"}
</head>

<body id="{$page_details[0].page_title}">
    <div id="page-container">
        {if isset($page_details[0].page_display_header) && $page_details[0].page_display_header == 1}
        <div id="header-section">
            {include file="header.tpl" title="Page header"}
        </div>
        {/if}
        <div id="content-section">
            <div id="content-container" class="container-fluid">
                <div id="top" class="row">
                    <div class="col-xs-12">
                        {include file="top.tpl" title="Page top"}
                    </div>
                </div>
                <div id="content-row" class="row">
                    {if $page_details[0].page_column_structure == 'left_column' ||
                    $page_details[0].page_column_structure ==
                    'full_column'}
                    <div id="left-column" class="col-xs-12 col-sm-3 col-lg-2">
                        {if isset($left_column_gap) && !empty($left_column_gap)}
                        {foreach $left_column_gap as $key => $value}
                        {$value}
                        {/foreach}
                        {/if}
                    </div>
                    {/if}
                    <div id="center-column" class="col-xs-12 {if $page_details[0].page_column_structure == 'full_width'}col-sm-12{elseif $page_details[0].page_column_structure == 'left_column' || $page_details[0].page_column_structure == 'right_column'}col-sm-9 col-lg-10{/if}">
                        {include file="alerts.tpl" title="Page alerts"}
                        <div class="page-header">
                            <h1 class="page-name">
                                {if (isset($page_details[0].page_head_title) &&
                                !empty($page_details[0].page_head_title))}
                                {$page_details[0].page_head_title}
                                {/if}
                            </h1>
                            <p class="page-description">
                                {if (isset($page_details[0].page_description) &&
                                !empty($page_details[0].page_description))}
                                {$page_details[0].page_description}
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
                    {if $page_details[0].page_column_structure == 'right_column' ||
                    $page_details[0].page_column_structure ==
                    'full_column'}
                    <div id="right-column" class="col-xs-12 col-sm-4">
                        {if isset($right_column_gap) && !empty($right_column_gap)}
                        {foreach $right_column_gap as $key => $value}
                        {$value}
                        {/foreach}
                        {/if}
                    </div>
                    {/if}
                </div>
                <div id="bottom" class="row">
                    <div class="col-xs-12">
                        {include file="bottom.tpl" title="Page bottom"}
                    </div>
                </div>
            </div>
        </div>
        {if isset($page_details[0].page_display_footer) && $page_details[0].page_display_footer == 1}
        <div id="footer-section">
            <div id="footer-container" class="container-fluid">
                <div id="footer-row" class="row">
                    <div class="col-xs-12">
                        {include file="footer.tpl" title="Page footer"}
                    </div>
                </div>
            </div>
        </div>
        {/if}
    </div>
</body>

</html>