<?php
if (isset($_POST["userName"]))
{
    session_start();
    session_destroy();
    session_unset();
    unset($_SESSION["loginUser"]);
    $_SESSION = array();
}
?>