# [OneAuth](http://www.github.com/)

OneAuth is a minimal and secure PHP User Authentication System specially designed to provide the essential functionality to manage users, allowing for full control and customization of the Front-End and the Back-End.

OneAuth is just one PHP file and one MySQL table.

Copyright (c) 2012 [Xavi Esteve](http://xaviesteve.com)

`This project is in a very early stage and not functional yet`


## Features

* Simple: Purely PHP, all the front-end and forms need to be developed independently
* Secure: Password is salted and encrypted, session is stored in a cookie
* Small: Everything is in one PHP file and one MySQL database
* Scalable: Provides basic CRUD functionality



## API

Methods to work with users:

### $oa->register (email*, password*, password2*)

Returns: User ID on success or `false`


### $oa->activate (token*)

Returns: `true` or `false`


### $oa->login (email*, password*)

Returns: `true` or `false`


### $oa->logout (url)

Returns: `true` or `false`

`url` redirects user there on success


### $oa->forgotpass (email*, password*)

Returns: `true` or `false`


### $oa->resetpass (token*, password*, password2*)

Returns: `true` or `false`


### $oa->changepass (password*, password2*)

Returns: `true` or `false`


### $oa->user (param)

Returns: user parameter value. If param is empty returns an array with all of them. (Available parameters are all except `password` and `token`)


### $oa->logged_in ()

Returns: `true` if logged in or `false`


### $oa->c (message)

If message, it adds to the console buffer.

If empty, it returns all the console buffer.



## CRUD

$oa->select (what = ‘*’, from*, where)

Example: update(‘id’, ‘users’, ‘email = “xavi@example.com”);
$oa->insert (into*, values*)

Returns: `ID` (or `true`) or `false`
$oa->update (table*, values*, where*)

Returns: `true` or `false`
$oa->delete (from*, where*)

Returns: `true` or `false`



## Database

* `id` (int20)
* `email` (varchar100)
* `password` (varchar255) salted and SHA1 encrypted
* `registered` (?)
* `lastactive` (?) Used to calculate login cookie time
* `token` (varchar255) Can be used for 1) User account activation key, 2) Cookie token, 3) Password reset
* `status` (int2) 0: unactivated, 1: active, 2: password forgot, 6: locked, 7: banned, 9: deleted
* `level` (int2) default: 1



## License

MIT Open Source License

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.