<?php
////////////////////////////////////////////////////////////////////////////////////////////////
//
//			 ,-----.                  ,---.            ,--.  ,--.     
//			'  .-.  ',--,--,  ,---.  /  O  \ ,--.,--.,-'  '-.|  ,---. 
//			|  | |  ||      \| .-. :|  .-.  ||  ||  |'-.  .-'|  .-.  |
//			'  '-'  '|  ||  |\   --.|  | |  |'  ''  '  |  |  |  | |  |
//			 `-----' `--''--' `----'`--' `--' `----'   `--'  `--' `--'
//
////////////////////////////////////////////////////////////////////////////////////////////////
//
//		OneAuth is a minimal and secure PHP User Authentication System specially designed
//		to provide the essential functionality to manage users, allowing for full control
//		and customization of the Front-End and the Back-End.
//
//		OneAuth is just one PHP file and one MySQL table.
//		
//		Copyright (c) 2012 Xavi Esteve http://xaviesteve.com
//		Documentation and latest source at https://github.com/
//		
//		MIT Open Source License
//		Permission is hereby granted, free of charge, to any person obtaining a copy of 
//		this software and associated documentation files (the "Software"), to deal in 
//		the Software without restriction, including without limitation the rights to use, 
//		copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the 
//		Software, and to permit persons to whom the Software is furnished to do so, 
//		subject to the following conditions:
//
//		The above copyright notice and this permission notice shall be included in all 
//		copies or substantial portions of the Software.
//
//		THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, 
//		INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A 
//		PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT 
//		HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION 
//		OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE 
//		SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
//
////////////////////////////////////////////////////////////////////////////////////////////////

class OneAuth {

	/*** Settings ***/
	private $debug = 	true;
	private $title = 	'Test';
	private $version = 	'0.1.0b';
	private $url = 		'http://localhost/hostgator/xaviesteve/github/oneauth'; // no trailing slash
	private $path = 	'/Users/xestevev/Dropbox/WWW/hostgator/xaviesteve/github'; 
	private $mysql = array(
		'server' => 	'localhost',
		'database' => 	'database',
		'username' => 	'root',
		'password' => 	'root',
	);
	private $hash = array(
		'login' => 	'JBHF62t8bt6twb8stN0&TO977rb^$£%@5veYtbUNT@*&@(P98m',
		'password' => 	'JHbfUKYtui6@*@t2no8niUNT(@&TY@MT&@(72nt9@&TB*@DYW*',
	);
	// In seconds
	private $length = array(
		'session' => 	1209600, // 1209600: 14 days
	);




	public function __construct() {

	}


}
$oa = new OneAuth;