<?php
////////////////////////////////////////////////////////////////////////////////////////////////
//
//			 ,-----.                  ,---.            ,--.  ,--.
//			'  .-.  ',--,--,  ,---.  /  O  \ ,--.,--.,-'  '-.|  ,---.
//			|  | |  ||      \| .-. :|  .-.  ||  ||  |'-.  .-'|  .-.  |
//			'  '-'  '|  ||  |\   --.|  | |  |'  ''  '  |  |  |  | |  |
//			 `-----' `--''--' `----'`--' `--' `----'   `--'  `--' `--'
//
//          by Xavi Esteve ( https://github.com/luckyshot/OneAuth )
//
////////////////////////////////////////////////////////////////////////////////////////////////
//
//		OneAuth is a minimal and secure PHP MySQL User Authentication System specially designed
//		to provide the essential functionality to manage users, allowing for full control
//		and customization of the Front-End and the Back-End.
//
//		OneAuth is just one PHP file and one MySQL table.
//
//		Copyright (c) 2012-2014 Xavi Esteve http://xaviesteve.com
//		Documentation and latest source at https://github.com/luckyshot/OneAuth
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

	private $version = 	'0.1.4';
	private $user = false;
	private $config = array();

	public function __construct( $config ) {
		$this->config = $config;
		require_once('db.php');
		$this->db = new DB ($this->config['mysql']['username'], $this->config['mysql']['password'], $this->config['mysql']['database']);
	}




	/**

	 * user
	 * Gets user details, current user if no ID specified
	 * Returns user data or false if no ID specified and user not logged in
	 */
	public function user( $id = '' ) {

		if (is_numeric( $id )) {
			$q = "SELECT * FROM ".$this->config['mysql']['table']." WHERE id = :id LIMIT 1";
			$user = $this->db->query($q)
				->bind(':id', $id)
				->single();

		// if no ID specified then current user
		}else{

			if ( !$this->user ) {

				if (!$_COOKIE['oa']) { return false; }

				$q = "SELECT * FROM ".$this->config['mysql']['table']." WHERE token = :token LIMIT 1;";
				$this->user = $this->db->query($q)
					->bind(':token', sha1($this->config['hash']['token'].$_COOKIE['oa']) )
					->single();
			}

			$user = $this->user;
		}

		// if current user and token is old then logout user
		if ( !$id && $user['token_expiry'] < time() ) {
			$this->logout();
			return false;

		// if current user and token still valid then refresh expiry date
		}else if ( !$id ) {
			$this->update_user_trace();
		}

		return $user;
	}





	/**

	 * register
	 * registers a new user
	 * Returns the user array including an activation link
	*/
	public function register( $data ) {

		// Preliminary checks
		if ( !$this->validate_email( $data['email'] ) ) { return array('error'=>'Email is not valid'); }

		if ( $this->email_exists( $data['email'] ) ) { return array('error'=>'Email already registered'); }

		if ($data['password'] !== $data['password2']) { return array('error'=>'Passwords do not match'); }

		if ( strlen($data['password']) < $this->config['minpasslength'] ) { return array('error'=>'Password is too short'); }

		// Salt password before storing it in the DB
		$data['password'] = sha1( $this->config['hash']['password'] . $data['password'] );

		// hash to use in the activation link or just to have something in there
		$data['hash'] = sha1( $this->config['hash']['token'].$this->randomchars() );

		$insertResponse = $this->db->query("INSERT INTO `".$this->config['mysql']['table']."` SET
				id = null,
				email = :email,
				password = :password,
				date_created = :date_created,
				date_seen = :date_seen,
				ip = :ip,
				flags = :flags,
				token = :token,
				token_expiry = :token_expiry;")
			->bind(':email', strtolower( $data['email']) )
			->bind(':password', $data['password'] )
			->bind(':date_created', date('Y-m-d H:i:s'))
			->bind(':date_seen', date('Y-m-d H:i:s'))
			->bind(':ip', $_SERVER['REMOTE_ADDR'])
			->bind(':flags', ($this->config['activation']) ? 'i' : '' ) // if activation required it will inactivate account
			->bind(':token', $data['hash'] )
			->bind(':token_expiry', date('Y-m-d H:i:s', time() + $this->config['session']) )
			->insert();

		// if mysql returns a number that's good, it's the ID of the new user
		if (is_numeric($insertResponse)) {
			$data['id'] = $insertResponse;

			// if activation not needed then login automatically, otherwise send email
			if ( !$this->config['activation'] ) {
				$this->login( $data['email'], $data['password2'] );
				unset($data['password2']);
			}else{
				$this->send_email('activate', $data, $data['email']);
			}

			return $data;

		}else{
			return array('error' => $insertResponse);
		}
	}









	/**

	 * activate
	 * activate user account
	 * Returns true or false
	*/
	public function activate( $hash ) {

		// find hash in DB
		$q = "SELECT * FROM ".$this->config['mysql']['table']." WHERE token = :token LIMIT 1;";
		$user = $this->db->query($q)
			->bind(':token', $hash )
			->single();

		// remove inactive flag
		if ( $user ) {
			$updatedFlags = $this->db->query("UPDATE ".$this->config['mysql']['table']." SET
				flags = :flags
				WHERE id = :id LIMIT 1;")
			->bind(':flags', str_replace('i', '', $user['flags']) )
			->bind(':id', $user['id'])
			->execute();

			if ( $updatedFlags ) {
				return true;
			}else{
				return array( 'error'=>'Error removing i flag' );
			}

		}else{
			return array( 'error'=>'User not found, invalid activation hash' );
		}
	}





	/**

	* edit
	* Edit user details
	*/
	public function edit( $data, $id = '' ) {
		$user = $this->user( $id );
		if (!$user) return false;

		if ( !$id ) {
			// check old password is correct
			$q = "SELECT * FROM ".$this->config['mysql']['table']." WHERE
				password = :password AND 
				id = :id
				LIMIT 1;";
			$correctPass = $this->db->query($q)
				->bind(':password', sha1( $this->config['hash']['password'] . $data['passwordold'] ) )
				->bind(':id', $this->user['id'] )
				->single();
			if ( !$correctPass ) {
				return array('error' => 'incorrect password');			
			}
		}

		if ( strlen($data['passwordnew']) > 0 && strlen($data['passwordnew']) < $this->config['minpasslength'] ) { return array('error'=>'Password is too short'); }

		if ( strlen($data['passwordnew'])>0 ) {
			if ( $data['passwordnew'] !== $data['passwordnew2'] ) { return array('error' => 'new password doesn\'t match'); }
			$password = $data['passwordnew'];
		}else{
			$password = $data['passwordold'];
		}

		// update fields
		$updateFields = $this->db->query("UPDATE ".$this->config['mysql']['table']." SET
			email = :email,
			password = ':password'
			WHERE id = :id LIMIT 1;")
		->bind(':email', $data['email'] )
		->bind(':password', sha1( $this->config['hash']['password'] . $password ) )
		->bind(':id', $user['id'] )
		->execute();

		return $updateFields;
	}





	/**

	 * delete
	 * Removes all identifiable information from user
	 * and sets a random password
	*/
	public function delete( $id = '' ) {
		$user = $this->user( $id );
		if ( !$user ) return false;

		$deleteUser = $this->db->query("UPDATE ".$this->config['mysql']['table']." SET
				email = '',
				password = ':password',
				date_seen = :date_seen,
				ip = :ip,
				flags = 'd',
				token = ':token'
				WHERE id = :id LIMIT 1;")
			->bind(':password', sha1( $this->randomchars()) )
			->bind(':date_seen', date('Y-m-d H:i:s'))
			->bind(':ip', $_SERVER['REMOTE_ADDR'])
			->bind(':token', sha1( $this->randomchars()) )
			->bind(':id', $user['id'])
			->execute();

		$this->logout();

		return $deleteUser;
	}




	/**

	 * forgot
	 * returns a reset hash that allows to change the password of the current account
	 * you will probably want to send this link by email
	*/
	public function forgot( $email ) {

		if ( !$this->email_exists( $email ) ) { return array('error' => "Email not registered"); }

		// create a hash
		$hash = sha1( $this->config->hash['reset'] . $user['id'] . $user['password'] );

		// set it as the token
		// this way logged in user will be kicked out but will be able to login again
		// and if he remembers the password the reset link will expire
		$updatesToken = $this->db->query("UPDATE ".$this->config['mysql']['table']." SET
				token = :token
				WHERE email = :email LIMIT 1;")
			->bind(':token', $hash)
			->bind(':email', $email)
			->execute();

		if ( $updatesToken ) {
			// now send email to user with reset link
			return $this->send_email( 'forgot', array('hash'=>$hash), $email );
		}else{
			return array('error' => "Could not update reset hash");
		}
	}



	/**

	 * reset
	 * Changes password to new one
	*/
	public function reset ($hash, $pass, $pass2) {

		if ($pass !== $pass2) {
			return array('error'=> 'Passwords do not match');
		}

		// check hash matches one in the DB
		$q = "SELECT * FROM ".$this->config['mysql']['table']." WHERE
			token = :token
			LIMIT 1;";
		$this->user = $this->db->query($q)
			->bind(':token', $hash )
			->single();

		if (!$this->user) { return array('error' => 'incorrect hash'); }

		// reset password of user to new one
		$result = $this->db->query("UPDATE ".$this->config['mysql']['table']." SET
			password = :password
			WHERE id = :id LIMIT 1;")
		->bind(':password', sha1( $this->config['hash']['password'] . $pass ) )
		->bind(':id', $this->user['id'])
		->execute();
		
		if ( $result ) {
			return true;
		}else{
			return array('error' => 'error reseting password');
		}
	}





	/**

	 * login
	 *
	*/
	public function login( $data ) {
		if ( !$this->email_exists( $data['email'] ) ) { return array('error' => "Email not registered"); }

		$q = "SELECT * FROM ".$this->config['mysql']['table']." WHERE
			email = :email AND
			password = :password AND
			flags NOT LIKE '%d%'
			LIMIT 1;";
		$user = $this->db->query($q)
			->bind(':email', $data['email'] )
			->bind(':password', sha1($this->config['hash']['password'].$data['password']) )
			->single();

		if (!$user) {
			return array('error' => 'login failed');
		}

		// is deleted account?
		if ($this->hasflag('d')) {
			return array('error' => 'login ok but account deleted');
		}		

		// needs activation?
		if ($this->config['activation'] && $this->hasflag('i')) {
			return array('error' => 'login ok but needs activation first');
		}

		// everything ok
		$this->user = $user;

		// set token in cookie
		$this->user['token'] = sha1( $this->randomchars() );
		setcookie( 'oa', $this->user['token'], (time() + $this->config['session']) );
		$_COOKIE['oa'] = $this->user['token'];

		// set token in DB
		$result = $this->db->query("UPDATE ".$this->config['mysql']['table']." SET
			token = :token
			WHERE id = :id 
			LIMIT 1;")
		->bind(':token', $this->user['token'] )
		->bind(':id', $this->user['id'])
		->execute();

		// update user trace
		$this->update_user_trace();

		return $this->user;
	}





	/**

	 * logout
	 * Logs out a user by deleting session cookies
	 * HTTP redirection is needed in order to fully remove session cookie
	*/
	public function logout() {
		$_COOKIE['oa'] = NULL;
		setcookie('oa', '', time()-3600*24*365);
		unset($this->user);
		return true;
	}


	/**

	 * hasflag
	 * Checks if user has a flag
	 * Return true or false
	*/
	public function hasflag( $flag, $id = '' ) {
		$user = $this->user( $id );
		if (!$user) return false;

		if (strpos($user['flags'], $flag) !== false) {
			return true;
		}else{
			return false;
		}
	}


























	/**

	 * update_user_trace
	 * updates date last seen, ip address and token expiry
	*/
	private function update_user_trace() {

		$result = $this->db->query("UPDATE ".$this->config['mysql']['table']." SET
			date_seen = :date_seen,
			token_expiry = :token_expiry,
			ip = :ip
			WHERE id = :id LIMIT 1;")
		->bind(':date_seen', date('Y-m-d H:i:s'))
		->bind(':token_expiry', date('Y-m-d H:i:s', time() + $this->config['session']) )
		->bind(':ip', $_SERVER['REMOTE_ADDR'])
		->bind(':id', $this->user['id'])
		->execute();

		return $result;
	}







	/**

	 * validate_email
	 * checks if it's a valid email
	*/
	private function validate_email( $email ) {
		if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
			return true;
		}else{
			return false;
		}
	}


	/**

	 * send_email
	 * Sends an email to someone based on a template with provided data
	*/
	private function send_email( $template, $data ) {


		$html = 
			$this->config['templates']['header'] . 
			$this->config['templates'][ $template ] . 
			$this->config['templates']['footer'];

		// Put all vars together
		$data = array_merge( $this->config, $data );
		foreach ($data as $key => $value) {
			// NOTE: array values are not allowed, this is both for security reasons and simplicity
			if (gettype($value)!='array') {
				$html = str_replace('{{'.$key.'}}', $value, $html);
			}
		}

		$mailer = array();
		$mailer['fromName'] = 	$data['app_name'];
		$mailer['fromEmail'] = 	$data['app_email'];
		$mailer['toEmail'] = 	$data['email'];
		$mailer['subject'] = 	$data['template_titles'][ $template ];
		$mailer['body'] = 		$html;
		$mailer['headers'] = 	"From: ".$mailer['fromName']." <". $mailer['fromEmail'].">\r\n".
								"Reply-To: ".$mailer['fromName']." <".$mailer['fromEmail'].">\r\n".
								"MIME-Version: 1.0\r\n".
								"Content-Type: text/html; charset=utf-8\r\n";

var_dump($mailer);
var_dump(mail( $mailer['toEmail'], $mailer['subject'], $mailer['body'], $mailer['headers']));
		return 123;
	}


	/**

	 * email_exists
	 * Checks if email is in the database
	*/
	private function email_exists( $email ) {

		if (!$this->validate_email( $email )) { return false; }

		$email = strtolower($email);

		$q = "SELECT * FROM ".$this->config['mysql']['table']." WHERE email = :email LIMIT 1";
		$user = $this->db->query($q)
			->bind(':email', $email)
			->single();

		return $user;
	}


	/**

	 *
	*/
	private function randomchars($length = 50) {
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
	}


}
