<?php

require_once('config.php');
require_once('oneauth.php');

$oa = new OneAuth($oaconfig);





if ($_POST['oa']=='login') {
	$oa->login(array(
		'email' => $_POST['email'],
		'password' => $_POST['password'],
	));
}else if ($_POST['oa']=='register') {
	$oa->login(array(
		'email' => $_POST['email'],
		'password' => $_POST['password'],
		'password2' => $_POST['password2'],
	));
}else if ($_POST['oa']=='logout') {
	$oa->logout();
}
?>

<h1>OneAuth Examples</h1>

<?php 
$user = $oa->user();

var_dump($user);

if ($user) { ?>

	<p>Hello <?php echo $user['email']; ?>! <a href="?oa=logout">Logout</a></p>

<?php }else{ ?>

	<p>Not logged in.</p>

	<h3>Login</h3>
	<form action="" method="post">
		<input type="hidden" name="oa" value="login">
		<input type="text" name="email" placeholder="Email">
		<input type="password" name="password" placeholder="Password">
		<input type="submit" value="Login">
	</form>

	<h3>Register account</h3>
	<form action="" method="post">
		<input type="hidden" name="oa" value="register">
		<input type="text" name="email" placeholder="Email">
		<input type="password" name="password" placeholder="Password">
		<input type="password" name="password2" placeholder="Repeat password">
		<input type="submit" value="Register">
	</form>

	<h3>Forgot password</h3>

	<form action="" method="post">
		<input type="hidden" name="oa" value="forgot">
		<input type="email" placeholder="Email">
		<input type="submit" value="Forgot password">
	</form>


<?php } ?>



<!--

<h3>Reset password</h3>

<form action="">
	<input type="hidden" name="oa" value="reset">
	<input type="password" name="password" placeholder="New password">
	<input type="password" name="password2" placeholder="Repeat password">
	<input type="submit" value="Set password">
</form>


<h3>Edit account</h3>

<form action="">
	<input type="hidden" name="oa" value="edit">
	<input type="text" name="email" value="" placeholder="Email">
	<input type="password" name="password" placeholder="Leave empty to keep current password">
	<input type="password" name="password2">
	<input type="submit" value="Save">
</form>

-->
