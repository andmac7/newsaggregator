<?php
    include("dbtools.php");
    session_start();

    if(isset($_SESSION['loginUser']))
    {
        header("Location: index.php");
    }

    if($_SERVER["REQUEST_METHOD"] == "POST")
    {
        $dbc = connectToDb();
        $userName = mysqli_real_escape_string($dbc,$_POST['userName']);
        $password = mysqli_real_escape_string($dbc,$_POST['password']);
        $hashedPassword = getUserPassword($userName,$dbc);
        
        if (password_verify($password, $hashedPassword))
        {
            $_SESSION['loginUser'] = $userName;
        }
        else
        {
            errorMessage("Could not log in, wrong username or password.", 400);
        }
    }
?>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>
    <script src="js/utilities.js"></script>
	<title>nooscaper login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script type="text/javascript">
    $(document).ready(function() {

        // Enable using enter when logging in
        $("#login-password").keyup(function(event) {
            if (event.keyCode === 13) {
                $("#login-btn").click();
            }
        });

        $("#login-btn").click(function() {
            var userName = $("#login-userName").val();
            var password = $("#login-password").val();
            $.ajax({
                url: 'login.php',
                type: 'POST',
                data: {'userName': userName, 'password': password},
                success: function() {
                    window.location = "index.php";
                },
                error: function(data) {
                    statusMessage('error', data.responseJSON.message);
                }
            });
        });
    });
    </script>

</head>
<header>
	<div class="text-center">
		<img id="title-logo" src="img/logo.png" alt="Title"></a>
	</div>
</header>
<body>
	<div class="centeredContent col-xs-1" align="center">
		<div id="login-prompt">
            <div id="status-msg"></div>
            <input id="login-userName" type="text" class="form-control" placeholder="Username" >
            <input id="login-password" type="password" class="form-control" placeholder="Password" >
			<button id="login-btn" type="submit" class="btn btn-primary">Log in</button>
        </div>
        <a href="register.php">
        Register an account
        </a>
	</div>
</body>