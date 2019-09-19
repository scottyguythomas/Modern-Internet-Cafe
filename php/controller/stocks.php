<?php
/*
*	Modification Log:
*	file: stocks.php
*	author : Scott Thomas
*	last modified: 2019-9-13 10:50 AM
*/

/* page containing misc. functions for boilerplate code */

function alert($message)
{
	echo "<script>";
  	echo "alert('$message')";
	echo "</script>";
}

function includeScript($message)
{
	echo "<script src='$message'></script>";
}

function script($message)
{
	echo "<script>";
  	echo $message;
	echo "</script>";
}

function comment($message)
{
	echo "<!-- " . htmlspecialchars($message) . " -->";
}
?>