<?php
	include 'dbtools.php';
	include 'api.php';

	session_start();

	if(!(isset($_SESSION['loginUser'])))
    {
        header("Location: login.php");
	}
	// Get db connection
	$dbc = connectToDb();
	// Get logged in user
	$userName = $_SESSION["loginUser"];
	$userId = getUserId($userName,$dbc);
	// Get logged in users saved words
	$searchWords = getSearchWords($userId, $dbc);
?>
<head>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
	<script src="js/utilities.js"></script>
	<script src="js/bootstrap/bootstrap.min.js"></script>
	<script type="text/javascript">
	// On dom load, init js functions
	$(document).ready(function() {
		initIndexPage();
	});
	</script>
	<title>Nooscaper</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css">
	<link rel="stylesheet" href="css/style.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
</head>
<header>
	<input id="user-name-field" type="hidden" value="<?php echo($_SESSION['loginUser']) ?>">
	<div class="text-center">
		<img id="title-logo" src="img/logo.png" alt="Title"></a>
	</div>
</header>
<body>
	<button id="logout-btn" class="btn btn-link">Log out</button>
	<div class="centeredContent col-xs-1" align="center">
		<div id="add-search-term">
			<input id="termId" type="text" class="form-control" name="searchWord" id="termId">
			<button id="add-btn" type="submit" class="btn btn-primary"><i class="fas fa-plus"></i></button>
		</div>

		<div id="searchTerms">
			<?php
				$arrlength = count($searchWords);
				for($i = 0; $i < $arrlength; $i++) 
				{
					echo("
						<div id='entry-".$searchWords[$i]."' class='word-btn'>".$searchWords[$i]."
							<div class='delete-btn' value=".$searchWords[$i].">
								<i class='fas fa-times-circle'></i>
							</div>
						</div>
					");
				}		
			?>
		</div>
		<div id="articles-list">
		</div>
	</div>
</body>