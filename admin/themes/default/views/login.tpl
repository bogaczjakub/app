<div class="row">
    <div class="col-xs-12 col-sm-4 col-sm-push-4 flex-center-column">
        <div id="login-container">
            <div class="panel panel-primary">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <span class="glyphicon glyphicon-lock login-lock" aria-hidden="true"></span>
                    <p class="login-plain-faq text-center">Enter your login and password to login into administration
                        panel.</p>
                    <form id="login-form" action="index.php?controller=Login&action=login" method="GET" target="_self"
                        class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" class="form-control" id="login-form_login" name="login-form_login"
                                    placeholder="login">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="password" class="form-control" id="login-form_password" name="login-form_password"
                                    placeholder="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" id="login-form_remember_me" value="1" name="remember_me">
                                        Remember me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 text-center">
                                <button type="submit" class="btn btn-default" id="login-form_submit" name="login-form_submit">Login</button>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 text-center">
                                <a href="">Remind me password</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>