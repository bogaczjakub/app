<!DOCTYPE html>
<html lang="{$global_page_details.site_language}">


<head>
    {include file="head.tpl" title="Page head"}
</head>

<body id="{$page_details[0].page_title}">
    <div id="page-container">
        <div id="header-section">
            {include file="header.tpl" title="Page header"}
        </div>
        <div id="content-section">
            <div id="master-container" class="container-fluid">
                <div id="master-row" class="row">
                    {if $page_details[0].column_structure == 'left_column' || $page_details[0].column_structure ==
                    'full_column'}<div id="master-left-column" class="col-xs-12 col-sm-3 col-lg-2"></div>{/if}
                    <div id="master-center-column" class="col-xs-12 {if $page_details[0].column_structure == 'full_width'}col-sm-12{elseif $page_details[0].column_structure == 'left_column' || $page_details[0].column_structure == 'right_column'}col-sm-9 col-lg-10{/if}">
                        {include file="$content"}
                    </div>
                    {if $page_details[0].column_structure == 'right_column' || $page_details[0].column_structure ==
                    'full_column'}<div id="master-left-column" class="col-xs-12 col-sm-4"></div>{/if}
                </div>
            </div>
        </div>
        <div id="footer-section">
            <div class="container">
                <div class="row">
                    {include file="footer.tpl" title="Page footer"}
                </div>
            </div>
        </div>
    </div>
</body>