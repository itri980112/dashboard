<?php
require('commonlib.php');
session_start();
if(isset($_SESSION['user_id'])){
    header("Location: ./index.php");
}
$error = false;
if(isset($_POST['account'])){
    $account = $_POST['account'];
    $passwd = $_POST['passwd'];
    $conn=getConnection('ibabymall');
    $id = login($conn, $account, $passwd);
    if($id != -1){
        $_SESSION['user_id'] = $id;
        header("Location: ./index.php");
    } else {
        $error = true;
    }
}
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="">
        <meta name="author" content="">
        <link rel="icon" href="">
        <title>麗嬰房登入系統</title>
        <!-- Bootstrap core CSS -->
        <link href="./assets/css/bootstrap.min.css" rel="stylesheet" media="screen">
        <link rel="stylesheet" href="http://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
        <script type="text/javascript"  src="http://code.jquery.com/jquery-1.10.2.js"></script>
        <script type="text/javascript" src="http://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
        <link href="./assets/jasny-bootstrap/css/jasny-bootstrap.min.css" rel="stylesheet" media="screen">
        <link href="./assets/sb-admin-2.css" rel="stylesheet">
        <link href="./assets/timeline.css" rel="stylesheet">
        <link href="./assets/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="./assets/c3.css" rel="stylesheet" type="text/css">
        <script src="./assets/js/typeahead.bundle.js"></script>
        <!-- Custom styles for this template -->
        <link href="./assets/dashboard.css" rel="stylesheet">
        <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
        <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
        <script src="./assets/ie-emulation-modes-warning.js"></script>
        <script src="./assets/c3.min.js"></script>
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <style>
            body{
                min-height: 100%;
                width: 100%;
                height: 100%;
                background-color: #101010;
                padding: 30%;
                padding-top: 10%;
                box-sizing: border-box;
            }
        </style>
    </head>
    <body>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h1>登入系統</h1>
            </div>
            <div class="panel-body">
                <form class="form" method="post">
                    <div class="form-group">
                        <label>帳號</label>
                        <input name="account" type="text" class="form-control" placeholder="Account">
                    </div>
                    <div class="form-group">
                        <label>密碼</label>
                        <input name="passwd" type="password" class="form-control" placeholder="Password">
                    </div>
                    <button class="btn btn-success">登入</button>
                    <!--button class="btn btn-success">註冊</button-->
                </form>
            </div>
        </div>
    </body>
    <script>
        $(document).ready({
        });
    </script>
</html>
