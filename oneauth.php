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
//		OneAuth is a minimal and secure PHP User Authentication System specially designed
//		to provide the essential functionality to manage users, allowing for full control
//		and customization of the Front-End and the Back-End.
//
//		OneAuth is just one PHP file and one MySQL table.
//
//		Copyright (c) 2012-2014 Xavi Esteve http://xaviesteve.com
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

	private $version = 	'0.0.1a';
	private $user = false;
	private $config = array();

	public function __construct($config) {
		$this->config = $config;
		require_once('db.php');
		$this->db = new DB ($this->config['mysql']['username'], $this->config['mysql']['password'], $this->config['mysql']['database']);
	}




	/**

	 * user
	 * Gets user details, current user if no ID specified
	 * Returns user data or false if no ID specified and user not logged in
	 */
	public function user ($id = '') {

		if (is_numeric(($id))) {
			$q = "SELECT * FROM ".$this->mysql['table']." WHERE id = :id LIMIT 1";
			$user = $this->db->query($q)
				->bind(':id', $id)
				->single();

			return $user;

		// if no ID specified then current user
		}else{
			// if user's cookie invalid then delete cookie
			if (strlen($_COOKIE['token']) != COOKIE_TOKEN_LENGTH) {
				$this->logout();
				return false;
			}

			if (!$this->user) {
				$q = "SELECT * FROM ".$this->mysql['table']." WHERE token = :token LIMIT 1;";
				$this->user = $this->db->query($q)
					->bind(':token', sha1($this->config['hash']['token'].$_COOKIE['token']) )
					->single();
			}
			return $this->user;
		}
	}





	/**

	 * create
	 * Creates a new user
	 * Returns the user array including an activation link
	*/
	public function create ($data) {

		// TODO: check if email already exists
		// TODO: validate email

		if ($data['password'] !== $data['password2']) {
			return -1;
		}

		$data['password'] = sha1($this->config['hash']['password'].$data['password']);

		$new_user = $this->db->query("INSERT INTO `".$this->mysql['table']."` SET
				id = null,
				email = :email,
				password = :password,
				date_created = :date_created,
				date_seen = :date_seen,
				ip = :ip,
				flags = :flags,
				token = :token;")
			->bind(':email', strtolower( $data['email']) )
			->bind(':password', $data['password'] )
			->bind(':date_created', date('Y-m-d H:i:s'))
			->bind(':date_seen', date('Y-m-d H:i:s'))
			->bind(':ip', $_SERVER['REMOTE_ADDR'])
			->bind(':flags', ($this->config['activation'] && $this->hasflag('i')) ? 'i' : '' )
			->bind(':token', sha1($this->config['hash']['token'].$this->randomchars()) )
			->insert();

		if (is_numeric($new_user)) {
			$data['id'] = $new_user;
			// activate link
			$data['link'] = sha1( $this->config->hash['activate'] . $data['password'] );

			$this->login($data['email'], $data['password2']);
			unset($data['password2']);

			return $data;
		}else{
			return $new_user;
		}

	}








	/**

	 * edit
	 * Edit user details
	*/
	public function edit ($data, $id = '') {

		// TODO

		return false;
	}





	/**

	 * delete
	 * Removes all identifiable information from user
	*/
	public function delete () {
		$user = $this->user();
		if (!$user) return false;

		return $this->db->query("UPDATE ".MYSQL_PREFIX." SET
				email = '',
				password = '',
				date_seen = :date_seen,
				ip = :ip,
				flags = 'd',
				token = ''
				WHERE id = :id LIMIT 1;")
			->bind(':date_seen', date('Y-m-d H:i:s'))
			->bind(':ip', $_SERVER['REMOTE_ADDR'])
			->bind(':id', $user['id'])
			->execute();
	}




	/**

	 * forgotpass
	 * returns a reset link that allows to change the password of the current account
	 * you will probably want to send this link by email
	*/
	public function forgotpass () {
		$user = $this->user();
		if (!$user) return false;

		// reset link
		return sha1( $this->config->hash['reset'] . $user['password'] );
	}



	/**

	 * resetpass
	 * Changes password to new one
	*/
	public function resetpass ($pass, $id = '') {
		$user = $this->user();
		if (!$user) return false;

		// TODO: reset password of user to new one

		return false;
	}





	/**

	 * login
	 *
	*/
	public function login ($data) {
		$q = "SELECT * FROM ".$this->mysql['table']." WHERE
			email = :email AND
			password = :password AND
			flags NOT LIKE '%d%'
			LIMIT 1;";
		$this->user = $this->db->query($q)
			->bind(':email', $data['email'] )
			->bind(':password', sha1($this->config['hash']['password'].$data['password']) )
			->single();

		if ($this->config['activation'] && $this->hasflag('i')) {
			return -2;
		}else{

			$_COOKIE['token'] = $this->randomchars();
			setcookie('token', $_COOKIE['token'], time() + $this->config['session']);

			$this->db->query("UPDATE ".MYSQL_PREFIX." SET
					date_seen = :date_seen,
					token = :token,
					ip = :ip
					WHERE id = :id LIMIT 1;")
				->bind(':date_seen', date('Y-m-d H:i:s'))
				->bind(':token', $_COOKIE['token'])
				->bind(':ip', $_SERVER['REMOTE_ADDR'])
				->bind(':id', $this->user['id'])
				->execute();
		}

		return $this->user;
	}





	/**

	 * logout
	 *
	*/
	public function logout () {
		$_COOKIE['token'] = NULL;
		setcookie('token', '', time()-3600*24*365);
		return false;
	}


	/**

	 *
	 *
	*/
	public function hasflag ($flag, $id = '') {
		$user = $this->user($id);
		if (!$user) return false;

		if (strpos($user['flags'], $flag) !== false) {
			return true;
		}else{
			return false;
		}
	}






	private function randomchars($length = 50) {
		$pool = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
		return substr(str_shuffle(str_repeat($pool, 5)), 0, $length);
	}


}
