<?php

date_default_timezone_set('Europe/Amsterdam');

$oaconfig = array(

	'app_name' => 'OneAuth',
	'app_email' => 'oneauth@example.com',

	'url' => 	'http://localhost/oneauth', // no trailing slash
	'path' => 	'/Users/xavi/www/OneAuth', // no trailing slash

	'mysql' => array(
		'server' => 	'localhost',
		'database' => 	'test',
		'table' => 		'users',
		'username' => 	'root',
		'password' => 	'root',
	),

	'salt' => array(
		'token' => 		'JBHF62t8bt6twb8stN0&TO977rb^£%@5veYtbUNT@*&@(P9-8m',
		'activate' => 	'n(p[:~9&g kdJ220Dl}[lK;/>,bSKoqPQjI@(DnID*^i8@OkDn',
		'reset' => 		'lJ79K0K@@_dj;S"kMd[=+-8JnmaKAi2oDWp@oi2\"dSMWDS;aJ',
	),

	'session' => 604800, // in seconds. 604800 = 7 days, 1209600 = 14 days,
	
	'minpasslength' => 8, // minimum password length
	
	'activation' => false, // require new users to click on the activation link
	
	'passwordcost' => 10, // password strength (4 to 31; each increment means that the password hashing is twice as expensive), default 10

	'template_titles' => array(
		'activate' => 'Activate your account',
		'forgot' => 'Reset your password',
	),

	'templates' => array(

		'header' => '<p>Hi there,</p>',

		'footer' => '<p>&nbsp;</p>
<p>Thanks,</p>
<p>The {{app_name}} Team</p>',

		'activate' => '<p>Please confirm your account by clicking <a href="{{url}}/?oa=activate&token={{token}}">here</a>.</p>',

		'forgot' => '<p>Please reset your password by clicking <a href="{{url}}/?oa=reset&id={{id}}&token={{token}}">here</a>.</p>',

	),

);
