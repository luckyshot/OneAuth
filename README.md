# [OneAuth](https://github.com/luckyshot/OneAuth) <br>PHP User Authentication Class with UAC features

OneAuth is a **secure** and **minimal** boilerplate PHP User Authentication System developed to provide essential functionality to manage user authentication on websites, ready to use and to build upon your web app, project, REST server, use it in a framework like Limonade or anything else.

* **Minimal**: OneAuth is coded to have the essential features, nothing more
* **Secure**: Passwords are salted and encrypted in SHA-1, users are authenticated through a cookie instead of a session file (increased security)
* **Small**: OneAuth is two PHP classes and one MySQL table
* **Scalable**: You can add new fields, integrate with other login methods (such as social media networks) and build new functionalities very easily


## Features

- Register new account
- Activate account through email link (optional)
- Edit account details (including password reprompt)
- Login/Logout
- Account deletion (removes identifiable information but keeps the user for historical reasons)
- Forgot/Reset password (user receives email with reset link)
- Flags to enable UAC (User Access Lists): admin levels, memberships or any other user categorization
- Secure salting and SHA-1 encryption of passwords and tokens
- Customizable session length
- MySQL uses PDO named parameters with a built-in debugging class


## Setup

1. Copy this into your files to initialize OneAuth:
<pre>require_once('config.php');
require_once('oneauth.php');

$oa = new OneAuth($oaconfig);</pre>

2. Modify <code>config.php</code> with your database details and change any other settings such as the hashes and project name

3. Check <code>index.php</code> for usage examples


## Class methods overview

For full documentation see the code at <code>oneauth.php</code>, it is full of comments and very easy to understand.

###### Account

- <code>$oa->user()</code>
- <code>$oa->register()</code>
- <code>$oa->activate()</code>
- <code>$oa->edit()</code>
- <code>$oa->delete()</code>

###### Session

- <code>$oa->login()</code>
- <code>$oa->logout()</code>

###### Password

- <code>$oa->forgot()</code>
- <code>$oa->reset()</code>

###### Flags

- <code>$oa->hasflag()</code>



## Flags

Flags are letters that can be used for User Access Control, user levels, account settings, admins&hellip; These are the two used right now:

- <code>d</code>: Deleted account (deleted accounts cannot log in)
- <code>i</code>: Inactive account (needs to confirm email address)



## Email templates

- <code>forgot</code>: 'Here is a link to reset your password'
- <code>activate</code>: 	'Thanks for registering, please activate your account'



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

To debug MySQL queries replace <code>new DB</code> with <code>new DBDebug</code> in <code>oneauth.php</code>, this will return the formed query without running it so you can analize it.


## TODO

- *Security* Update SHA1 to [password_hash()|http://php.net/manual/en/book.password.php]
- *Security* Individual salt ( globalSalt + individualSalt + password ) [info|http://stackoverflow.com/questions/3897434/password-security-sha1-sha256-or-sha512]
- *Security* Limit failed attempts (in login and in reset)
- *Feature* Simple user management dashboard


## Test

### Register

- :white_check_mark: Wrong email fails
- :white_check_mark: If email already exists then cannot re-register
- :white_check_mark: Missmatching passwords fails
- :white_check_mark: If password is too short fails
- :white_check_mark: Creates MySQL row with all fields
- :white_check_mark: If activation = true then flag = i
- :white_check_mark: If activation = true then email is sent
- [  ] If activation = false then no email, no flag and login automatically

### Activate

- :white_check_mark: Flag <code>i</code> is removed from account

### Login

- :white_check_mark: Checks email and pass match
- :white_check_mark: Gets user info
- [  ] Checks if needs activation
- :white_check_mark: Generates new token
- :x: New token is stored in cookie
- :x: New token is stored in DB

### Forgot

- :white_check_mark: Detects email not registered
- :white_check_mark: Creates reset hash
- :white_check_mark: Updates token in DB with hash
- [  ] Sends reset email

### Reset password

- [  ] Old password is requested and verified
- [  ] New password is set
- [  ] Token is refreshed so relogin is necessary

### Edit account

- [  ] ...

### Delete account

- [  ] Personal information is removed
- [  ] Flag <code>d</code> is added to user
- [  ] Password and token are randomised

### Logout

- [  ] Cookies are deleted
- [  ] token_expiry is set to past


## License

**MIT Open Source License**

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

_THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE._
