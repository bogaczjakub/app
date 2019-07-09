<div id="jumbotron">
    <div class="jumbotron container">
        <h1>Hello, world!</h1>
        <p>Some additional text down here.</p>
        <p>Another line of additional text.</p>
        <button class="btn btn-success btn-lg" href="#" role="button">Learn more</button>
    </div>
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