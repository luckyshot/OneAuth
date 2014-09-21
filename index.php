<?php

// Load required files
require_once('config.php');
require_once('oneauth.php');

// Initialize class
$oa = new OneAuth($oaconfig);



// Routing
$action = $_REQUEST['oa'];

// Register
if ($action=='register') {

	$user = $oa->register(array(
		'email' => $_POST['email'],
		'password' => $_POST['password'],
		'password2' => $_POST['password2'],
	));

	if ($user['error']) {
		$msg = $user['error'];
	}else{
		$msg = 'Welcome '.$user['email'].'!';
	}

// Activate account
}else if ($action=='activate') {
	$activation = $oa->activate( $_REQUEST['hash'] );

	if ($activation['error']) {
		$msg = $activation['error'];
	}else{
		$msg = 'Account activated!';
	}

// Login
}else if ($action=='login') {

	$user = $oa->login(array(
		'email' => $_POST['email'],
		'password' => $_POST['password'],
	));

	if ($user['error']) {
		$msg = $user['error'];
	}else{
		$msg = 'Hello '.$user['email'].'!';
	}

// Forgot password
}else if ($action=='forgot') {

	$forgot = $oa->forgot( $_POST['email'] );

	// WARNING: an attacker should never be able to tell if an email exists in the DB,
	//          always return a message like 'if that email is in the DB we have sent a reset link'
	if ($forgot['error']) {
		$msg = $forgot['error'];
	}else{
		$msg = 'No problem! We\'ve sent you an email';
	}

// Reset password
}else if ($action=='reset') {
	$reset = $oa->reset(
		$_POST['hash'],
		$_POST['password'],
		$_POST['password2']
	);

	if ($reset['error']) {
		$msg = $reset['error'];
	}else{
		$msg = 'Your password has been reset successfully, you can now login';
	}


}else if ($action=='edit') {
	$oa->edit(array(
		// TODO
	));

}else if ($action=='logout') {
	$oa->logout();

	$msg = 'you are now logged out';

}else if ($action=='delete') {

	$delete = $oa->delete();

	if ($delete['error']) {
		$msg = $delete['error'];
	}else{
		$msg = 'Your account has been deleted successfully, good bye!';
	}


}


// Load user
$user = $oa->user();

?><!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>OneAuth Examples</title>
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.1.1/css/bootstrap.min.css">
	</head>
	<body>


<h1>OneAuth Examples</h1>

<?php if ($msg) { echo '<h2><center>'.$msg.'</center></h2>'; } ?>

<?php if ( $user ) { ?>

	<h3>User data</h3>
	<?php var_dump( $user ); ?>

	<h3>Logout</h3>
	<p><a href="?oa=logout">Logout</a></p>

	<h3>Edit account</h3>
	<form action="">
		<input type="hidden" name="oa" value="edit">
		<input type="text" name="email" value="" placeholder="Email">
		<input type="password" name="passwordnew" placeholder="Leave empty to keep current password">
		<input type="password" name="passwordnew2" placeholder="">

		<input type="password" name="passwordold" placeholder="Current password">
		<input type="submit" value="Save">
	</form>

	<h3>Delete account</h3>
	<p><small><a href="?oa=delete" style="color:#c00">Delete account</a></small></p>

<?php }else{ ?>

	<p>Not logged in.</p>

	<h3>Register account</h3>
	<form action="" method="post">
		<input type="hidden" name="oa" value="register">
		<input type="text"   name="email" placeholder="Email">
		<input type="password" name="password" placeholder="Password">
		<input type="password" name="password2" placeholder="Repeat password">
		<input type="submit" value="Register">
	</form>

	<h3>Activate</h3>
	<form action="" method="get">
		<input type="hidden" name="oa" value="activate">
		<input type="text"   name="hash" placeholder="Activation code">
		<input type="submit" value="Activate">
	</form>

	<h3>Login</h3>
	<form action="" method="post">
		<input type="hidden" name="oa" value="login">
		<input type="email"  name="email" placeholder="Email">
		<input type="password" name="password" placeholder="Password">
		<input type="submit" value="Login">
	</form>

	<h3>Forgot password</h3>

	<form action="" method="post">
		<input type="hidden" name="oa" value="forgot">
		<input type="email"  name="email" placeholder="Email">
		<input type="submit" value="Forgot password">
	</form>

	<h3>Reset password</h3>

	<form action="" method="post">
		<input type="hidden" name="oa" value="reset">
		<input type="hidden" name="hash">
		<input type="password" name="password" placeholder="New password">
		<input type="password" name="password2" placeholder="Repeat new password">
		<input type="submit" value="Reset password">
	</form>

<?php } ?>



</body>
</html>
