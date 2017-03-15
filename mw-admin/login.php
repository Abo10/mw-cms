<?php
require_once 'config.php';
require_once '../mod.php';

session_set_cookie_params(3600000, "/");
session_start();
//$_SESSION['asdas']='asdasd';
//var_dump($_SESSION);die;
if (isset($_GET['logout']) && $_GET['logout'] == 'yes') {
    CUserAdmin::LogOut();
    header("Location: " . ADMIN_URL . 'login.php');
    die;

}
if (CUserAdmin::Initial()) {
    header("Location: " . ADMIN_URL . 'index.php');
    die;

}
if (isset($_POST['submit'])) {
    CUserAdmin::Initial();
    if (CUserAdmin::Authentication(['login' => $_POST['login'], 'password' => $_POST['pass']])) {

        header("Location: " . ADMIN_URL . 'index.php');
        die;
    } else {
        $message = " Wrong Username or Password";
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WA Admin Login</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        body, html {
            height: 100%;
            background-repeat: no-repeat;
            background-image: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));

        }

        .card-container.card {
            max-width: 350px;
            padding: 40px 40px;
        }

        .btn {
            font-weight: 700;
            height: 36px;
            -moz-user-select: none;
            -webkit-user-select: none;
            user-select: none;
            cursor: default;
        }

        /*
         * Card component
         */
        .card {
            background-color: #F7F7F7;
            /* just in case there no content*/
            padding: 20px 25px 30px;
            margin: 0 auto 25px;
            margin-top: 50px;
            /* shadows and rounded borders */
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            border-radius: 2px;
            -moz-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            -webkit-box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
            box-shadow: 0px 2px 2px rgba(0, 0, 0, 0.3);
        }

        .profile-img-card {
            width: 96px;
            height: 96px;
            margin: 0 auto 10px;
            display: block;
            -moz-border-radius: 50%;
            -webkit-border-radius: 50%;
            border-radius: 50%;
        }

        /*
         * Form styles
         */
        .profile-name-card {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0 0;
            min-height: 1em;
        }

        .reauth-email {
            display: block;
            color: #404040;
            line-height: 2;
            margin-bottom: 10px;
            font-size: 14px;
            text-align: center;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .form-signin #inputEmail,
        .form-signin #inputPassword {
            direction: ltr;
            height: 44px;
            font-size: 16px;
        }

        .form-signin input[type=email],
        .form-signin input[type=password],
        .form-signin input[type=text],
        .form-signin button {
            width: 100%;
            display: block;
            margin-bottom: 10px;
            z-index: 1;
            position: relative;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }

        .form-signin .form-control:focus {
            border-color: rgb(104, 145, 162);
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgb(104, 145, 162);
            box-shadow: inset 0 1px 1px rgba(0, 0, 0, .075), 0 0 8px rgb(104, 145, 162);
        }

        .btn.btn-signin {
            /*background-color: #4d90fe; */
            background-color: rgb(104, 145, 162);
            /* background-color: linear-gradient(rgb(104, 145, 162), rgb(12, 97, 33));*/
            padding: 0px;
            font-weight: 700;
            font-size: 14px;
            height: 36px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            border: none;
            -o-transition: all 0.218s;
            -moz-transition: all 0.218s;
            -webkit-transition: all 0.218s;
            transition: all 0.218s;
        }

        .btn.btn-signin:hover,
        .btn.btn-signin:active,
        .btn.btn-signin:focus {
            background-color: rgb(12, 97, 33);
        }

        .forgot-password {
            color: rgb(104, 145, 162);
        }

        .forgot-password:hover,
        .forgot-password:active,
        .forgot-password:focus {
            color: rgb(12, 97, 33);
        }
    </style>
</head>
<body>
<!--
    you can substitue the span of reauth email for a input with the email and
    include the remember me checkbox
    -->
<div class="container">
    <div class="card card-container">
        <!-- <img class="profile-img-card" src="//lh3.googleusercontent.com/-6V8xOA6M7BA/AAAAAAAAAAI/AAAAAAAAAAA/rzlHcD0KYwo/photo.jpg?sz=120" alt="" /> -->
        <img id="profile-img" class="profile-img-card" src="//ssl.gstatic.com/accounts/ui/avatar_2x.png"/>

        <p id="profile-name" class="profile-name-card"></p>

        <form class="form-signin" method="POST">
            <span id="reauth-email" class="reauth-email"></span>
            <input type="text" id="inputEmail" class="form-control" name="login" placeholder="Login" required autofocus>
            <input type="password" id="inputPassword" class="form-control" name="pass" placeholder="Password" required>
            <!--            <div id="remember" class="checkbox">-->
            <!--                <label>-->
            <!--                    <input type="checkbox" value="remember-me"> Remember me-->
            <!--                </label>-->
            <!--            </div>-->
            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="submit">Sign in</button>
            <?php
            if (isset($message)) {
                ?>
                <div class="alert alert-danger" role="alert">
                    <?= $message ?>
                </div>
            <?php } ?>

        </form><!-- /form -->
        <!--        <a href="#" class="forgot-password">-->
        <!--            Forgot the password?-->
        <!--        </a>-->
    </div><!-- /card-container -->
</div><!-- /container -->
</body>
</html>