<?php
require_once "dbtools.php";
include "parser.php";

function deleteWordApi($userName, $wordId)
{
	$dbc = connectToDb();
	$userId = getUserId($userName, $dbc);
	deleteSearchWord($userId, $wordId, $dbc);
}

function addWordApi($userName, $searchWord)
{
	$dbc = connectToDb();
	$userId = getUserId($userName, $dbc);
	insertUserSearchWord($userId, $searchWord, $dbc);
}

function getArticles($userName)
{
	$dbc = connectToDb();
	$userId = getUserId($userName, $dbc);
	$searchWords = getSearchWords($userId, $dbc);
	$feed = parseXmlFeed($searchWords);
	echo(json_encode($feed));
}

// Find out what the call intends to do
$action = '';
if (isset($_POST['action']))
{
	$action = $_POST['action'];
} 
else if (isset($_GET['action']))
{
	$action = $_GET['action'];
}

// Control that all relevant parameters are set for delete
if (isset($_POST['action']) && isset($_POST['wordId']) && isset($_POST['userName']))
{
	if ($action == 'deleteWord')
	{
		deleteWordApi($_POST['userName'], $_POST['wordId']);
	}
}

// Add word
if (isset($_POST['action']) && isset($_POST['searchWord']) && isset($_POST['userName']))
{
	if ($action == 'addWord')
	{
		addWordApi($_POST['userName'], $_POST['searchWord']);
	}
}

// Get articles
if (isset($_GET['action']) && isset($_GET['userName']))
{
	if ($action == 'getArticles')
	{
		getArticles($_GET['userName']);
	}
}
?>