<nav id="front-navbar" class="navbar navbar-default">
    <div class="container">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
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
    </div>
</nav>
{if isset($header_gap) && !empty($header_gap)}
{foreach $header_gap as $key => $value}
{$value}
{/foreach}
{/if}