<div class="row">
    <div class="col-xs-12 col-sm-4 col-sm-push-4 flex-center-column">
        <div id="login">
            <div class="panel panel-primary">
                <div class="panel-heading">Login</div>
                <div class="panel-body">
                    <span class="glyphicon glyphicon-lock login-lock" aria-hidden="true"></span>
                    <p class="login-plain-faq text-center">Enter your login and password to login into administration
                        panel.</p>
                    <form id="login_form" action="{if isset({$template_data.login_form_action}) && !empty({$template_data.login_form_action})}{$template_data.login_form_action}{/if}" method="POST" target="_self"
                        class="form-horizontal">
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="text" name="login_form-login" id="login_form-login" class="form-control"
                                    placeholder="login">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <input type="password" name="login_form-password" id="login_form-password" class="form-control"
                                    placeholder="password">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12">
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="login_form-remember_me" value="1" id="login_form-remember_me">
                                        Remember me
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-12 text-center">
                                <button type="submit" name="login_form-submit" id="login_form-submit" class="btn btn-default">Login</button>
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