# [OneAuth](https://github.com/luckyshot/OneAuth) <br>PHP User Authentication Class with ACL features

OneAuth is a **highly secure** and **small** PHP User Authentication System developed to provide functionality to manage user authentication on websites, ready to use and to build upon your project.

* **Small**: OneAuth is two PHP classes and one MySQL table
* **Scalable**: You can add new fields, integrate with other login methods (such as social media networks) and build new functionalities very easily
* **Secure**: Passwords are salted locally and globally and encrypted using the best PHP supported algorythm, users are authenticated through a cookie instead of a session file (increased security) and it is linked to the device's IP address



## Features

- Activate account through email link (optional)
- Edit account details (including old password reprompt)
- Delete account (removes identifiable information but keeps the user for historical reasons)
- Forgot password (user receives email with reset link)
- ACL flags (Access Control List): great for admin levels, memberships or any other user categorization
- Industry standard secure local+global salting encryption of passwords and tokens
- Customizable session length, password encryption strength, salts...
- MySQL uses PDO named parameters with a built-in debugging class
- Comprehensible error messages to make debugging easier
- Custom and flexible email templates



## Requirements

- PHP 5.5+ (5.3.7+ compatibility below)
- MySQL



## Setup

1. Modify <code>config.php</code> with your URL, database, salts (salt generator available at the bottom of <code>index.php</code>), project name and email, session length, email templates, etc.

2. Run <code>dump.sql</code> in your MySQL

3. Copy this into your project to initialize OneAuth:
<pre>require_once('config.php');
require_once('oneauth.php');
$oa = new OneAuth($oaconfig);</pre>

4. Open <code>index.php</code> to see usage examples ready to copy-paste into your project

5. Delete <code>index.php</code>



## Class methods overview

For full documentation see the code at <code>oneauth.php</code>, it is full of comments and very easy to understand.

###### Account

- <code>$oa->user()</code>
- <code>$oa->register()</code>
- <code>$oa->activate()</code>
- <code>$oa->edit()</code>
- <code>$oa->delete()</code>

###### Password

- <code>$oa->forgot()</code>
- <code>$oa->reset()</code>

###### Session

- <code>$oa->login()</code>
- <code>$oa->logout()</code>

###### Flags

- <code>$oa->hasflag()</code>



## Flags

Flags are letters that can be used for User Access Control, user levels, account settings, admins&hellip; These are the two used right now:

- <code>d</code>: Deleted account (deleted accounts cannot log in)
- <code>i</code>: Inactive account (needs to confirm email address)



## Email templates

- <code>forgot</code>: Contains a link to reset the password
- <code>activate</code>: Contains a link to activate the account
- <code>welcome</code>: Contains welcome message



## Database

* `id` (bigint20)
* `email` (varchar100)
* `password` (char40)
* `date_created` (datetime)
* `date_seen` (datetime)
* `ip` (varchar15)
* `flags` (varchar10)
* `token` (char40)
* `token_expiry` (datetime)

To debug MySQL queries replace <code>new DB</code> with <code>new DBDebug</code> in <code>oneauth.php</code>, this will return the formed query without executing it so you can analize it.



### Salting and hashing

Here's how passwords and tokens are salted and hashed:

- Passwords: <code>password_hash( globalSalt + password, PASSWORD_DEFAULT )</code>
- Session token: <code>sha1( globalSalt + IpAddress + $this->randomchars() )</code>
- Reset password token: <code>sha1( globalSalt + userId + $this->randomchars() )</code>



## PHP 5.3.7+ Compatibility

Since OneAuth uses <code>password_hash()</code> it needs PHP 5.5+ to work, for those under 5.3.7+ here is the workaround (<a href="http://php.net/manual/en/function.password-hash.php#113490">more info</a>):

<pre>$salt = mcrypt_create_iv(22, MCRYPT_DEV_URANDOM);
$salt = base64_encode($salt);
$salt = str_replace('+', '.', $salt);
$hash = crypt('rasmuslerdorf', '$2y$10$'.$salt.'$');</pre>



## Credits

OneAuth is developed by [Xavi Esteve|http://xaviesteve.com/]. Feel free to submit Issues and Pull requests.


## OneAuth License

You are free to use OneAuth in any other project (even commercial projects) as long as the copyright header is left intact.

_**MIT Open Source License**_

_Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:_

_The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software._

_THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE._
