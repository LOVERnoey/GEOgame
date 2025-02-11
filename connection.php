<?php

$dbhost = "localhost";
$dbuser = "s0030";
$dbpass = "0639767333159lol";
$dbname = "GEO_game";

if(!$con = mysqli_connect($dbhost,$dbuser,$dbpass,$dbname))
{

	die("failed to connect!");
}
