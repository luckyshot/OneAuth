<?php

error_reporting(E_ALL);

// Load required files
require_once( 'config.php' );
require_once( 'oneauth.php' );


class OneAuthTest extends OneAuth {

	public $config = array();

    	function __construct( $config ) {
    		$this->config = $config;
		require_once('db.php');
		$this->db = new DB ($this->config['mysql']['username'], $this->config['mysql']['password'], $this->config['mysql']['database']);
    	}


	protected function send_email($template, $data) {
		return true;
	}

	public function curl($url, $postfields = array()) {

		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, count($postfields));
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postfields) );

		curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_6_8) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.83 Safari/535.11');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the result on success, false on error
		return curl_exec($ch);
		curl_close($ch);
	}

}


// Initialize class
$oa = new OneAuthTest( $oaconfig );


$img = array(
	'pass' => '<img class="ico" src="https://assets-cdn.github.com/images/icons/emoji/unicode/2705.png" alt="Passed">',
	'fail' => '<img class="ico" src="https://assets-cdn.github.com/images/icons/emoji/unicode/274c.png" alt="FAILED">',
);




?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>OneAuth Test</title>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
		<style>
.ico {float:left;margin:0 1em;width:1em;}
		</style>
	</head>
	<body>



<h1>OneAuth Test</h1>

<table class="table">

<tr><td colspan="2"><h3>Register</h3></td></tr>


	<tr><td>Wrong email fails</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'register', 'email'=>'notanemail' ) );

	if (strpos($response, 'Email is not valid')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>


	<tr><td>Missmatching passwords fails</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'register', 'email'=>'test@gmail.com','password'=>'testtest','password2'=>'loremipsum' ) );

	if (strpos($response, 'Passwords do not match')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>

	<tr><td>If password is too short fails</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'register', 'email'=>'test@gmail.com','password'=>'t','password2'=>'t' ) );

	if (strpos($response, 'Password is too short')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>


	<tr><td>Registers OK</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'register', 'email'=>'test@gmail.com','password'=>'testtest','password2'=>'testtest' ) );

	if (strpos($response, 'Registration OK')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>

	<tr><td>If email already exists then cannot re-register</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'register', 'email'=>'test@gmail.com','password'=>'testtest','password2'=>'testtest' ) );

	if (strpos($response, 'Email already registered')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>


<tr><td colspan="2"><h3>Activate</h3></td></tr>

	<tr><td>Flag <code>i</code> is removed from account</td></tr>


<tr><td colspan="2"><h3>Login</h3></td></tr>


	<tr><td>Wrong password</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'login', 'email'=>'test@gmail.com','password'=>'foobar' ) );

	if (strpos($response, 'Wrong password')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>

	<tr><td>Email not found</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'login', 'email'=>'test2@gmail.com','password'=>'testtest' ) );

	if (strpos($response, 'Email not found')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>

	<tr><td>Case sensitive password</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'login', 'email'=>'test@gmail.com','password'=>'TesTTest' ) );

	if (strpos($response, 'Wrong password')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>

	<tr><td>Good login</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'login', 'email'=>'test@gmail.com','password'=>'testtest' ) );

	if (strpos($response, 'Login OK')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>

	<tr><td>Don't remember me session is short</td></tr>

	<tr><td>Remember me session is longer</td></tr>


<!-- 	<tr><td>Case-sensitive query</td></tr>
	<tr><td>Gets user info</td></tr>
	<tr><td>Checks if needs activation</td></tr>
	<tr><td>Generates new token</td></tr>
	<tr><td>New token is stored in cookie</td></tr>
	<tr><td>New token is stored in DB</td></tr>
 -->
<tr><td colspan="2"><h3>Forgot</h3></td></tr>

	<tr><td>Detects email not registered</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'reset', 'email'=>'test@gmail.com' ) );

	if (strpos($response, 'Email not registered')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>

<!-- 	<tr><td>Creates reset hash</td></tr> -->

<!-- 	<tr><td>Updates token in DB with hash</td></tr> -->

	<tr><td>Sends reset email</td><td><?php
	$response = $oa->curl( $oa->config['url'].'/',
		array( 'oa'=>'reset', 'email'=>'test@gmail.com' ) );

	if (strpos($response, 'Email sent OK')) { echo $img['pass']; }
	else{ echo $img['fail']; }
?></td></tr>

<tr><td colspan="2"><h3>Reset password</h3></td></tr>

	<tr><td>New password is set</td></tr>
	<tr><td>Why is user logging in automatically?</td></tr>

<tr><td colspan="2"><h3>Edit account</h3></td></tr>

	<tr><td>...</td></tr>

<tr><td colspan="2"><h3>Logout</h3></td></tr>

	<tr><td>Cookies are deleted</td></tr>
	<tr><td><code>token_expiry</code> is set to past</td></tr>

<tr><td colspan="2"><h3>Delete account</h3></td></tr>

	<tr><td>Personal information is removed</td></tr>
	<tr><td>Flag <code>d</code> is added to user</td></tr>
	<tr><td>Password and token are randomised</td></tr>





</table>

</body>
</html>