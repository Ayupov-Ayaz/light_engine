
<div class="container">
    <div class="row">

        <div class="col-md-offset-3 col-md-6">
            <form class="form-horizontal" method="post" action="/login/auth">
                <span class="heading">АВТОРИЗАЦИЯ</span>
                <div class="form-group">
                    <input type="text" name="login" class="form-control" id="login" placeholder="login">
                    <i class="fa fa-user"></i>
                </div>
                <div class="form-group help">
                    <input type="password" name="password" class="form-control" id="password" placeholder="Password">
                    <i class="fa fa-lock"></i>
                    <a href="#" class="fa fa-question-circle"></a>
                </div>
                <div class="form-group">
                    <div class="main-checkbox">
                        <input type="checkbox" value="none" id="remember" name="remember"/>
                        <label for="remember"></label>
                    </div>
                    <span class="text">Запомнить</span>
                    <button type="submit" class="btn btn-default">ВХОД</button>
                </div>
            </form>
        </div>

    </div><!-- /.row -->
</div><!-- /.container -->