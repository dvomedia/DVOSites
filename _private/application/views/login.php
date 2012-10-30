<h1>Login</h1>
You need to login to access this area
<br />
<br />
<form method="post" action="/login">
	<input type="hidden" name="return_url" value="<?php echo $return_url; ?>" />
	Username: <input type="text" placeholder="Enter your Username" name="username" />
	<br />
	Passwords: <input type="password" name="password" />
	<br />
	<input type="submit" value="Submit" />
</form>