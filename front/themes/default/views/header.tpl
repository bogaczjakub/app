<nav id="front-navbar" class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#collapse-container"
                aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="" class="navbar-brand">
                Site title
            </a>
        </div>
        {if isset($header_gap['modules']['navigationModule']) && !empty($header_gap['modules']['navigationModule'])}
            {$header_gap['modules']['navigationModule']}
        {/if}
    </div>
</nav>
{if isset($header_gap['modules']) && !empty($header_gap['modules'])}
{foreach from=$header_gap['modules'] key=$key item=$module}
{if $key !='navigationModule'}
$module
{/if}
{/foreach}
{/if}
{if isset($header_gap['blocks']) && !empty($header_gap['blocks'])}
{foreach $header_gap['blocks'] as $block}
{$block}
{/foreach}
{/if}