# [OneAuth](https://github.com/luckyshot/OneAuth) <br><small style="opacity:.66">PHP User Authentication Library</small>

OneAuth is a **secure** and **simple** boilerplate PHP User Authentication System designed to provide the essential functionality to manage users, ready to use or build upon.


## Features

* **Simple**: OneAuth is coded keeping simplicity in mind
* **Secure**: Passwords are salted and encrypted in SHA-1, users keep authenticated through a cookie instead of a session file
* **Small**: Just two PHP classes and one MySQL database
* **Scalable**: You can add new fields or integrate social media networks easily


## API

Methods to work with users (full documentation in the code):

###### Account

- $oa->user()
- $oa->new()
- $oa->activate()
- $oa->edit()
- $oa->delete()

###### Session

- $oa->login()
- $oa->logout()

###### Password

- $oa->forgotpass()
- $oa->resetpass()

###### Flags

- $oa->hasflag()



## Flags

Flags are letters that signify something. They can be used to create a User Access Control (s: subscriber, e: editor, 

<code>d</code>: Deleted account

<code>i</code>: Inactive account (needs to confirm email address)


## Forms

Example forms ready to copy-paste:

### Register account

<textarea name="" id="" cols="30" rows="10"><form action="">
		<input type="hidden" name="oa" value="register">
		<input type="text" name="email" placeholder="Email">
		<input type="password" name="password" placeholder="Password">
		<input type="password" name="password2" placeholder="Repeat password">
		<input type="submit" value="Register">
	</form></textarea>


### Login

<form action="">
	<input type="hidden" name="oa" value="login">
	<input type="text" name="email" placeholder="Email">
	<input type="password" name="password" placeholder="Password">
	<input type="submit" value="Login">
</form>


### Forgot password

<form action="">
	<input type="hidden" name="oa" value="forgot">	<input type="email" placeholder="Email">
	<input type="submit" value="Forgot password">
</form>


### Reset password

<form action="">
	<input type="hidden" name="oa" value="reset">	<input type="password" name="password" placeholder="New password">
	<input type="password" name="password2" placeholder="Repeat password">
	<input type="submit" value="Set password">
</form>


### Edit account

<form action="">
	<input type="hidden" name="oa" value="edit">
	<input type="text" name="email" value="" placeholder="Email">
	<input type="password" name="password" placeholder="Leave empty to keep current password">
	<input type="password" name="password2">
	<input type="submit" value="Save">
</form>




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