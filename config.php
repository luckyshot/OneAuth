<?php

date_default_timezone_set('Europe/Amsterdam');

$oaconfig = array(

	'app_name' => 'OneAuth',
	'app_email' => 'oneauth@example.com',

	'url' => 	'http://localhost/hostgator/xaviesteve/github/OneAuth', // no trailing slash

	'mysql' => array(
		'server' => 	'localhost',
		'database' => 	'test',
		'table' => 		'users',
		'username' => 	'root',
		'password' => 	'root',
	),

	'salt' => array(
		'password' => 	'Y**;29B}|}8B82laU@bAnUio`|[{)vIeZ/sAM3!8HFZG^5jV~Ss@?G-_U8;<n+8(',
		'token' => 		']TpWpiZ6rn0DF-}dbNHJ61P09m{a&3NYdlR1Tlknr]G2@r{ 46x-Th{4z]HLHJ.|',
		'activate' => 	'f`5IPJx]hQ~JrBgBI=b-|Mchq?y7fhgN_LM|!4.UWii6?+ykYse0Ly,85 (s/(|`',
		'reset' => 		'Yw*<s,sha8$A&Acc=UX)N/=g=)ed/wYjW8o}sl2)Mxplr!-+MRusG$2#a3/.*rFW',
	),

	'session' => 604800, // in seconds. 604800 = 7 days, 1209600 = 14 days
	'session_short' => 1800, // in seconds. 1800 = 30 min

	'minpasslength' => 8, // minimum password length

	'activation' => false, // require new users to click on the activation link

	'passwordcost' => 10, // password strength (4 to 31; each increment means that the password hashing is twice as expensive), default 10

	'template_titles' => array(
		'activate' => 'Activate your {{app_name}} account',
		'forgot' => 'Reset your {{app_name}} password',
		'welcome' => 'Welcome to {{app_name}}',
	),

	'templates' => array(

		'header' =>
'<p>Hi there,</p>',

		'footer' =>
'<p>&nbsp;</p>
<p>Thanks,</p>
<p>The {{app_name}} Team</p>',


		'activate' =>
'<p>Please confirm your account by clicking <a href="{{url}}/?oa=activate&token={{token}}">here</a>.</p>',

		'forgot' =>
'<p>Please reset your password by clicking <a href="{{url}}/?oa=reset&id={{id}}&token={{token}}">here</a>.</p>',

		'welcome' =>
'<h3>Thanks for joining {{app_name}}!</h3>
<p>We’re thrilled to welcome you to our community.</p>'

	),

);
