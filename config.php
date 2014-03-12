<?php

$oaconfig = array(
	
	'url' => 	'http://localhost/oneauth', // no trailing slash
	'path' => 	'/Users/xavi/www/OneAuth', // no trailing slash

	'mysql' => array(
		'server' => 	'localhost',
		'database' => 	'test',
		'table' => 		'users',
		'username' => 	'root',
		'password' => 	'root',
	),

	'hash' => array(
		'password' => 	'JHbfUKYtui6@*@t2no8niUNT(@&TY@MT&@(72nt9@&TB*@DYW*',
		'token' => 		'JBHF62t8bt6twb8stN0&TO977rb^£%@5veYtbUNT@*&@(P9-8m',
		'activate' => 	'n(p[:~9&g kdJ220Dl}[lK;/>,bSKoqPQjI@(DnID*^i8@OkDn',
		'reset' => 		'lJ79K0K@@_dj;S"kMd[=+-8JnmaKAi2oDWp@oi2\"dSMWDS;aJ',
	),
	
	'session' => 1209600, // in seconds. 1209600 = 14 days

);
