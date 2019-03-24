<nav id="admin-navbar" class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1"
                aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a href="" class="navbar-brand">
                <span class="glyphicon glyphicon-cog admin-icon"></span>
                Admin panel
            </a>
        </div>
        <form id="search-form" method="POST" target="_self" class="navbar-form navbar-left">
            <div class="btn-group">
                <input type="text" class="form-control search-input" placeholder="Search" name="search-form_search_input">
                <input type="hidden" class="form-control search-selector-input" name="search-form_search_selector_input">
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-default dropdown-toggle search-selector" data-toggle="dropdown"
                        aria-haspopup="true" aria-expanded="false" name="search-form_search-selector">
                        <span class="search-selector-text"></span>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu search-selector-list">
                        <li><a href="users">Users</a></li>
                        <li><a href="categories">Categories</a></li>
                        <li><a href="posts">Posts</a></li>
                        <li><a href="mailbox">Mailbox</a></li>
                        <li><a href="pages">Pages</a></li>
                    </ul>
                </div>
                <button type="submit" class="btn btn-primary search-submit"><span class="glyphicon glyphicon-search"></span></button>
            </div>
        </form>
        <ul id="admin-shortcut-dropdown" class="nav navbar-nav admin-navbar-dropdown">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                    aria-expanded="false"><span class="glyphicon glyphicon-star"></span></span>Shortcuts <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#">Separated link</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#">One more separated link</a></li>
                </ul>
            </li>
        </ul>
        <ul id="admin-user-dropdown" class="nav navbar-nav navbar-right admin-navbar-dropdown">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                    aria-expanded="false"><span class="glyphicon glyphicon-user"></span>Welcome, {if
                    isset($session.logged_user.user_name) &&
                    !empty($session.logged_user.user_name)}{$session.logged_user.user_name}{/if} <span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#"><span class="dropdown-text">Langugage</span></a></li>
                    <li><a href="#"><span class="dropdown-text">Settings</span></a></li>
                    <li><a href="#"><span class="dropdown-text">Mailbox</span></a></li>
                    <li role="separator" class="divider"></li>
                    <li class="logout-li"><a target="_self" href="{$page_url}&module[name]=login&module[action]=logout"><span
                                class="glyphicon glyphicon-share"></span><span class="dropdown-text">Logout</span></a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
{if isset($header_gap) && !empty($header_gap)}
{foreach $header_gap as $key => $value}
{$value}
{/foreach}
{/if}