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
        $password = password_hash(mysqli_real_escape_string($dbc,$_POST['password']), PASSWORD_DEFAULT);
        $email = mysqli_real_escape_string($dbc,$_POST['email']);
        $validateEmail = filter_var($email, FILTER_VALIDATE_EMAIL);
        $userExists = checkUserNameExists($userName, $dbc);
        if ($userExists == false && $validateEmail)
        {
            insertUser($userName, $password, $email, $dbc);
        }
        else
        {
            errorMessage("Could not create user.", 400);
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
        $("#register-btn").click(function() {
            var userName = $("#register-userName").val();
            var password = $("#register-password").val();
            var email = $("#register-email").val();
            if (isEmail(email)) {
                $.ajax({
                    url: 'register.php',
                    type: 'POST',
                    data: {'userName': userName, 'password': password, 'email': email },
                    success: function() {
                        $("#register-prompt").fadeOut("slow", function(){
                            $("#register-prompt").after("<h2 class='msg-success'>Successfully created account!</h2>");
                            setTimeout(function(){ window.location = "login.php"; }, 2000);
                        });
                    },
                    error: function(data) {
                        statusMessage('error', data.responseJSON.message);
                    }
                });
            } else {
                alert("Wrong e-mail format.");
            }
        });

        $("input.form-control").on({
            keydown: function(e) {
                if (e.which === 32)
                return false;
            },
            change: function() {
                this.value = this.value.replace(/\s/g, "");
            }
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
		<div id="register-prompt">
            <div id="status-msg"></div>
            <input id="register-userName" type="text" class="form-control" placeholder="Username" >
            <input id="register-password" type="password" class="form-control" placeholder="Password" >
            <input id="register-email" type="text" class="form-control" placeholder="E-mail" >
            <button id="register-btn" type="submit" class="btn btn-primary">Register Account</button>
            <a href="login.php">
            I already have an account
            </a>
        </div>
	</div>
</body>