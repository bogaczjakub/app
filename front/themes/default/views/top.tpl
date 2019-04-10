<div id="jumbotron">
    <div class="jumbotron container">
        <h1>Hello, world!</h1>
        <p>Some additional text down here.</p>
        <p>Another line of additional text.</p>
        <button class="btn btn-success btn-lg" href="#" role="button">Learn more</button>
    </div>
</div>
{if isset($top_gap) && !empty($top_gap)}
{foreach $top_gap as $key => $value}
{$value}
{/foreach}
{/if}