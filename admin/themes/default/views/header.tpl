<nav id="admin-navbar" class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <a class="navbar-brand">CMS</a>
        </div>
        <form class="navbar-form navbar-left">
            <div class="form-group">
                <input type="text" class="form-control" placeholder="Search">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
        <ul id="admin-user-dropdown" class="nav navbar-nav navbar-right">
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true"
                    aria-expanded="false"><span class="glyphicon glyphicon-user"></span>Dropdown<span class="caret"></span></a>
                <ul class="dropdown-menu">
                    <li><a href="#">Action</a></li>
                    <li><a href="#">Another action</a></li>
                    <li><a href="#">Something else here</a></li>
                    <li role="separator" class="divider"></li>
                    <li><a href="#"><span class="glyphicon glyphicon-share"></span>Logout</a></li>
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