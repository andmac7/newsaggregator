<?php
//----------------------------------------- CONNECT ----------------------------------------- //
function connectToDb()
{
	// DB login
	define ("DB_HOST", "127.0.0.1");
	define ("DB_USER","root");
	define ("DB_PASS","");
	define ("DB_NAME","nuscape");
	$dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME)
		or die ("Could not connect to the database");

	// Enable to install tables
	createTableUser($dbc);
	createTableUserSearchWords($dbc);

	if (mysqli_connect_errno($dbc)) 
	{ 
		errorMessage("Failed to connect to MySQL: " . mysqli_connect_error(), 500); 
    } 
	return $dbc;
}
//============================================================================================//
//------------------------------------ RETURN MESSAGES --------------------------------------- //
function errorMessage($message, $code)
{
	header('HTTP/1.1 500 Internal Server Error');
	header('Content-Type: application/json; charset=UTF-8');
	die(json_encode(array('code' => $code, 'message' => $message)));
}

function successMessage($message)
{
	//header('Content-Type: application/json');
	//echo(json_encode(array('success'=>'true', 'message'=>$message)));
}
//============================================================================================//
//------------------------------------- INSERT ---------------------------------------------- //
function insertUserSearchWord($userId, $searchWord, $dbc)
{
	$query = "INSERT INTO tbl_user_search_words (userID, searchWord) VALUES ('".$userId."', '".$searchWord."')";

	$result = mysqli_query($dbc, $query);

	if(!$result)
	{
		errorMessage("Could not add word to user ".$userId.".", 400);		
	} else
	{
		successMessage($result);
	}
}

function insertSearchWord($searchWord, $dbc)
{
	$query = "INSERT INTO tbl_search_words (searchWord) VALUES ('".$searchWord."')";

	$result = mysqli_query($dbc, $query);

	if(!$result)
	{
		errorMessage("Could not add search word.", 400);		
	} else
	{
		successMessage($result);
	}
}

function insertUser($userName, $password, $email, $dbc)
{
	$query = "INSERT INTO tbl_users (userName, pw, email) VALUES ('".$userName."', '".$password."', '".$email."')";
	$result = mysqli_query($dbc, $query);

	if(!$result)
	{
		errorMessage("Could not create user ".$userName, 400);		
	} else
	{
		successMessage($result);
	}
}
//============================================================================================//
//----------------------------------- SELECT ------------------------------------------------ //

function checkUserNameExists($userName, $dbc)
{
	$query = "SELECT userName FROM tbl_users WHERE userName = '".$userName."'";
	$result = mysqli_query($dbc, $query);
	if(!$result)
	{
		successMessage("User created!");
	} else
	{
		return false;
		errorMessage("User already exits", 405);
	}
}

function getUserPassword($userName, $dbc)
{
	$query = "SELECT pw FROM tbl_users WHERE userName = '".$userName."'";
	$result = mysqli_query($dbc, $query);
	if(!$result)
	{
		errorMessage("Could not verify user.", 500);		
	} else
	{
		while ($row = $result->fetch_assoc()) {
			return $row['pw'];
		}
	}
}

function getUserId($userName, $dbc)
{
	$query = "SELECT ID FROM tbl_users WHERE userName = '".$userName."'";
	$result = mysqli_query($dbc, $query);
	if(!$result)
	{
		errorMessage("Could not fetch user id", 500);		
	} else
	{
		while ($row = $result->fetch_assoc()) {
			return $row['ID'];
		}
	}
}

function getWordId($word, $dbc)
{
	$query = "SELECT ID FROM tbl_search_words WHERE searchWord = '".$word."'";
	$result = mysqli_query($dbc, $query);
	if(!$result)
	{
		errorMessage("Could not verify user.", 500);		
	} else
	{
		while ($row = $result->fetch_assoc()) {
			return $row['ID'];
		}
	}
}

function getSearchWords($userId, $dbc)
{
	$list = array();
	$query = "SELECT searchWord
			FROM tbl_user_search_words
			WHERE userID = '".$userId."'";
	$result = mysqli_query($dbc, $query);
	if(!$result)
	{
		errorMessage("Couldn't fetch search words", 500);
	} else {
		$i = 0;
		while ($row = mysqli_fetch_array($result))
		{
			$list[$i] = $row['searchWord'];
			$i++;
		}
		return $list;
	}
}
//============================================================================================//
//------------------------------------- DELETE ---------------------------------------------- //
function deleteSearchWord($userId, $searchWord, $dbc)
{
	$query = "DELETE FROM tbl_user_search_words WHERE userID = '".$userId."' AND searchWord = '".$searchWord."'";
	
	$result = mysqli_query($dbc, $query);

	if(!$result)
	{
		errorMessage("Could not delete word.", 500);		
	} else
	{
		successMessage($result);
	}
}
//============================================================================================//
//------------------------------------- DROP --------  -------------------------------------- //
function dropSearchWords($dbc)
{
	$query = "DROP TABLE tbl_search_words";
	$result = mysqli_query($dbc, $query);

	if(!$result)
	{
		errorMessage("Could not drop table.", 500);		
	} else
	{
		successMessage($result);
	}
}
//============================================================================================//
//---------------------------------------- TABLE CREATION ------------------------------------//
function createTableSearchWords($dbc) 
{
	$query = "CREATE TABLE IF NOT EXISTS tbl_search_words (
			ID int NOT NULL AUTO_INCREMENT,
			searchWord varchar(255) NOT NULL,
			created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			UNIQUE (searchWord),
			CONSTRAINT pk_searchWord PRIMARY KEY(ID))";

	$result = mysqli_query($dbc, $query);

	if(!$result)
	{
		errorMessage("Could not create table.", 500);		
	} else
	{
		successMessage($result);
	}
}

function createTableUser($dbc) 
{
	$query = "CREATE TABLE IF NOT EXISTS tbl_users (
			ID int NOT NULL AUTO_INCREMENT,
			userName varchar(255) NOT NULL,
			pw varchar(255) NOT NULL,
			email varchar(255) NOT NULL,
			created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			UNIQUE (userName),
			CONSTRAINT pk_user PRIMARY KEY(ID))";

	$result = mysqli_query($dbc, $query);

	if(!$result)
	{
		errorMessage("Could not create table.", 500);		
	} else
	{
		successMessage($result);
	}
}

function createTableUserSearchWords($dbc) 
{
	$query = "CREATE TABLE IF NOT EXISTS tbl_user_search_words (
			userID varchar(255) not null,
			searchWord varchar(255) not null,
			created datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
			CONSTRAINT pk_userSearchWord PRIMARY KEY(userID, searchWord),
			FOREIGN KEY (userID) REFERENCES tbl_users(ID)
			)";

	$result = mysqli_query($dbc, $query);

	if(!$result)
	{
		errorMessage("Could not create table.", 500);		
	} else
	{
		successMessage($result);
	}
}
//============================================================================================//

?>