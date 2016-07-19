<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="SeeMore">
    <link rel="shortcut icon" href="images/favicon.png">

    <title>SeeMore | Admin Control Panel</title>
    <script type="text/javascript">var root_url = "<?php echo $config['LIVE_URL']; ?>" </script>
	
    <!--Core CSS -->
    <link href="<?php echo $config['LIVE_URL']; ?>views/bs3/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?php echo $config['LIVE_URL']; ?>views/css/bootstrap-reset.css" rel="stylesheet">
    <link href="<?php echo $config['LIVE_URL']; ?>views/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!-- Custom styles for this template -->
    <link href="<?php echo $config['LIVE_URL']; ?>views/css/style.css" rel="stylesheet">
    <link href="<?php echo $config['LIVE_URL']; ?>views/css/style-responsive.css" rel="stylesheet" />

    <!-- Just for debugging purposes. Don't actually copy this line! -->
    <!--[if lt IE 9]>
    <script src="<?php echo $config['LIVE_URL']; ?>views/js/ie8-responsive-file-warning.js"></script><![endif]-->

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
</head>

  <body class="login-body">

    <div class="container" >

      <form class="form-signin" action="" method="post"  name="login-form" id="login-form">
        <h2 class="form-signin-heading">sign in now</h2>
        <div align="center" id="error_msg" style="color:red;"></div>
        <div class="login-wrap">
            <div class="user-login-info">
                <input type="text" class="form-control" name="username" id="username" placeholder="User Name" autofocus>
                <input type="password" class="form-control" name="password" id="password" placeholder="Password">
            </div>
           <!--  <label class="checkbox">
                <input type="checkbox" value="remember-me"> Remember me
                <span class="pull-right">
                    <a data-toggle="modal" href="#myModal"> Forgot Password?</a>

                </span>
            </label> -->
            <button class="btn btn-lg btn-login btn-block" onclick="doLogin();" type="button">Sign in</button>
            <!-- <input type="submit"  class="btn btn-lg btn-login btn-block" onclick="doLogin();" vaue="Sign in"> -->

            <!-- <div class="registration">
                Don't have an account yet?
                <a class="" href="registration.html">
                    Create an account
                </a>
            </div> -->

        </div>

          <!-- Modal -->
          <div aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" id="myModal" class="modal fade">
              <div class="modal-dialog">
                  <div class="modal-content">
                      <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                          <h4 class="modal-title">Forgot Password ?</h4>
                      </div>
                      <div class="modal-body">
                          <p>Enter your e-mail address below to reset your password.</p>
                          <input type="text" name="recovery_mail" placeholder="Email" autocomplete="off" class="form-control placeholder-no-fix">

                      </div>
                      <div class="modal-footer">
                          <button data-dismiss="modal" class="btn btn-default" type="button">Cancel</button>
                          <button class="btn btn-success" onclick="forgotPassword();" type="button">Submit</button>
                      </div>
                  </div>
              </div>
          </div>
          <!-- modal -->

      </form>

    </div>



    <!-- Placed js at the end of the document so the pages load faster -->

    <!--Core js-->
    <script src="<?php echo $config['LIVE_URL']; ?>views/js/jquery.js"></script>
    <script src="<?php echo $config['LIVE_URL']; ?>views/bs3/js/bootstrap.min.js"></script>
    <script src="<?php echo $config['LIVE_URL']; ?>views/js/login.js"></script>
	

  </body>
</html>
