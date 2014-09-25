<?php

// Load required files
require_once( 'config.php' );
require_once( 'oneauth.php' );

// Initialize class
$oa = new OneAuth( $oaconfig );



/**
 * ROUTER
 */
$action = $_REQUEST['oa'];
$msgClass = 'alert-success';

// Register
if ($action=='register') {

	$user = $oa->register(array(
		'email' => $_POST['email'],
		'password' => $_POST['password'],
		'password2' => $_POST['password2'],
	));

	if ($user['error']) {
		$msgClass = 'alert-danger';
		$msg = $user['error'];
	}else{
		$msg = 'Registration OK';
	}

// Activate account
}else if ($action=='activate') {
	$activation = $oa->activate( $_REQUEST['token'] );

	if ($activation['error']) {
		$msgClass = 'alert-danger';
		$msg = $activation['error'];
	}else{
		$msg = 'Account activated OK';
	}

// Login
}else if ($action=='login') {

	$user = $oa->login(array(
		'email' => $_POST['email'],
		'password' => $_POST['password'],
	));

	if ($user['error']) {
		$msgClass = 'alert-danger';
		$msg = $user['error'];
	}else{
		$msg = 'Login OK';
	}

// Forgot password
}else if ($action=='forgot') {

	$forgot = $oa->forgot( $_POST['email'] );

	// WARNING: an attacker should never be able to tell if an email exists in the DB,
	//          always return a message like 'if that email is in the DB we have sent a reset link'
	if ($forgot['error']) {
		$msgClass = 'alert-danger';
		$msg = $forgot['error'];
	}else{
		$msg = 'Email sent OK';
	}

// Reset password
}else if ($action=='reset' && isset($_POST['password'])) {

	$reset = $oa->reset(
		$_POST['id'],
		$_POST['token'],
		$_POST['password'],
		$_POST['password2']
	);

	if ($reset['error']) {
		$msgClass = 'alert-danger';
		$msg = $reset['error'];
	}else{
		$msg = 'Password reset OK';
	}

// Reset link
}else if ($action=='reset' && !isset($_POST['password'])) {

	$msg = 'Reset link clicked OK, please type new password';


}else if ($action=='edit') {
	$edit = $oa->edit(array(
		'email' => $_POST['email'],
		'passwordold' => $_POST['passwordold'],
		'passwordnew' => $_POST['passwordnew'],
		'passwordnew2' => $_POST['passwordnew2'],
	));

	if ($edit['error']) {
		$msgClass = 'alert-danger';
		$msg = $edit['error'];
	}else{
		$msg = 'Edit OK';
	}


}else if ($action=='logout') {
	$oa->logout();

	$msg = 'Logged out OK';

}else if ($action=='delete') {

	$delete = $oa->delete();

	if ($delete['error']) {
		$msgClass = 'alert-danger';
		$msg = $delete['error'];
	}else{
		$msg = 'Account deleted OK';
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


<?php if ($msg) { echo '<div class="alert '.$msgClass.'">'.$msg.'</div>'; } ?>


<?php if ($user) { ?>
	<p>Hello <strong><?php echo $user['email']; ?></strong>! How are you doing?</p>
<?php }else{ ?>
	<p>You are logged out.</p>
<?php } ?>




<hr>





<h2>Logged out forms</h2>

<h3>Register account</h3>
<form action="./" method="post">
	<input type="hidden" name="oa" value="register">
	<input type="text"   name="email" placeholder="Email">
	<input type="password" name="password" placeholder="Password">
	<input type="password" name="password2" placeholder="Repeat password">
	<input type="submit" value="Register">
</form>

<h3>Activate</h3>
<form action="./" method="get">
	<input type="hidden" name="oa" value="activate">
	<input type="text"   name="token" placeholder="Activation code">
	<input type="submit" value="Activate">
</form>

<h3>Login</h3>
<form action="./" method="post">
	<input type="hidden" name="oa" value="login">
	<input type="email"  name="email" placeholder="Email">
	<input type="password" name="password" placeholder="Password">
	<input type="submit" value="Login">
</form>

<h3>Forgot password</h3>

<form action="./" method="post">
	<input type="hidden" name="oa" value="forgot">
	<input type="email"  name="email" placeholder="Email">
	<input type="submit" value="Forgot password">
</form>

<h3>Reset password</h3>
<form action="./" method="post">
	<input type="hidden" name="oa" value="reset">
	<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>">
	<input type="hidden" name="token" value="<?php echo $_GET['token']; ?>">
	<input type="password" name="password" placeholder="New password">
	<input type="password" name="password2" placeholder="Repeat new password">
	<input type="submit" value="Save new password">
</form>




<hr>





<h2>Logged in forms</h2>

<h3>Logout</h3>
<p><a href="?oa=logout">Logout</a></p>

<h3>Edit account</h3>
<form action="./" method="post">
	<input type="hidden" name="oa" value="edit">
	<input type="text" name="email" value="<?php echo $user['email']; ?>" placeholder="Email">
	<input type="password" name="passwordnew" placeholder="New password" title="Leave empty to keep current password">
	<input type="password" name="passwordnew2" placeholder="Repeat new password">

	<input type="password" name="passwordold" placeholder="Current password">
	<input type="submit" value="Save">
</form>

<h3>Delete account</h3>
<form action="./" method="post">
	<input type="hidden" name="oa" value="delete">
	<input type="submit" style="color:#c00;font-size:90%;" value="Delete account">
</form>


<h3>User data</h3>
<pre><?php var_dump( $user ); ?></pre>

<h3>Salts</h3>
<pre>	'salt' => array(
		'password' => 	'<?php echo $oa->randomchars(64); ?>',
		'token' => 		'<?php echo $oa->randomchars(64); ?>',
		'activate' => 	'<?php echo $oa->randomchars(64); ?>',
		'reset' => 		'<?php echo $oa->randomchars(64); ?>',
	),</pre>


</body>
</html>
