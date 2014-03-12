# [OneAuth](https://github.com/luckyshot/OneAuth) <br>PHP User Authentication Class Library with UAC features

OneAuth is a **secure** and **minimal** boilerplate PHP User Authentication System developed to provide essential functionality to manage user authentication on websites, ready to use and to build upon.

* **Minimal**: OneAuth is coded to have the essential features, nothing more
* **Secure**: Passwords are salted and encrypted in SHA-1, users keep authenticated through a cookie instead of a session file
* **Small**: Two PHP classes and one MySQL database
* **Scalable**: You can add new fields or integrate social media networks easily


## Features

- Account registration
- Account activation (optional)
- Login/Logout
- Account deletion
- Forgot/Reset password
- Flags to enable UAC, user levels, memberships or any other user categorization
- Secure salting and SHA-1 encryption of passwords
- Customizable session length
- Deleting an account removes identifiable information but keeps the user ID reserved
- Uses PDO named parameters with a built-in debugging class (see <code>db.php</code>)

## Setup

1. Copy the code in <code>index.php</code> into your files to initialize OneAuth
2. Modify <code>config.php</code> with your database details and change any other settings such as the hashes

## API

Methods to work with users (full documentation in the code):

###### Account

- <code>$oa->user()</code>
- <code>$oa->create()</code>
- <code>$oa->activate()</code>
- <code>$oa->edit()</code>
- <code>$oa->delete()</code>

###### Session

- <code>$oa->login()</code>
- <code>$oa->logout()</code>

###### Password

- <code>$oa->forgotpass()</code>
- <code>$oa->resetpass()</code>

###### Flags

- <code>$oa->hasflag()</code>



## Flags

Flags are letters that can be used for User Access Control, user levels, account settings, admins&hellip; These are the two used right now:

<code>d</code>: Deleted account (deleted accounts cannot log in)

<code>i</code>: Inactive account (needs to confirm email address)


## Forms

Example forms ready to copy-paste:

### Register account

<pre>&lt;form action=""&gt;
	&lt;input type="hidden" name="oa" value="register"&gt;
	&lt;input type="text" name="email" placeholder="Email"&gt;
	&lt;input type="password" name="password" placeholder="Password"&gt;
	&lt;input type="password" name="password2" placeholder="Repeat password"&gt;
	&lt;input type="submit" value="Register"&gt;
&lt;/form&gt;</pre>

### Login

<pre>&lt;form action=""&gt;
	&lt;input type="hidden" name="oa" value="login"&gt;
	&lt;input type="text" name="email" placeholder="Email"&gt;
	&lt;input type="password" name="password" placeholder="Password"&gt;
	&lt;input type="submit" value="Login"&gt;
&lt;/form&gt;</pre>

### Forgot password

<pre>&lt;form action=""&gt;
	&lt;input type="hidden" name="oa" value="forgot"&gt;
	&lt;input type="email" placeholder="Email"&gt;
	&lt;input type="submit" value="Forgot password"&gt;
&lt;/form&gt;</pre>

### Reset password

<pre>&lt;form action=""&gt;
	&lt;input type="hidden" name="oa" value="reset"&gt;
	&lt;input type="password" name="password" placeholder="New password"&gt;
	&lt;input type="password" name="password2" placeholder="Repeat password"&gt;
	&lt;input type="submit" value="Set password"&gt;
&lt;/form&gt;</pre>

### Edit account

<pre>&lt;form action=""&gt;
	&lt;input type="hidden" name="oa" value="edit"&gt;
	&lt;input type="text" name="email" value="" placeholder="Email"&gt;
	&lt;input type="password" name="password" placeholder="Leave empty to keep current password"&gt;
	&lt;input type="password" name="password2"&gt;
	&lt;input type="submit" value="Save"&gt;
&lt;/form&gt;</pre>




## Database

* `id` (bigint20)
* `email` (varchar100)
* `password` (char40)
* `date_created` (datetime)
* `date_seen` (datetime)
* `ip` (varchar15)
* `flags` (varchar10)
* `token` (char40)





## License

**MIT Open Source License**

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

_THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE._
