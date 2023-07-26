Audit_MR Module
==============

This is an unofficial module and requires some modifications to two core MunkiReport files.

Audits and lists login, logout, and login failures to MunkiReport. Resulting audit can be viewed in the `Login Audit` admin tab.



 
File Modifications
---
Two files need to be edited in order for this module to be able to capture and record logins

* **`/munkireport-php/app/controllers/Auth.php`**

	**Line ~5** - Change: 
	
	`use \Controller, \View;`
	
	To: 
	
	`use \Controller, \View, \Audit_mr_model;`

	**Line ~109** - Change: 
			  
	```
	} else {
	     error('Wrong username or password', 'auth.wrong_user_or_pass');
	}
	```
	
	To: 
	
	```
	} else {
	     // Load and audit the login
	     $audit = new Audit_mr_model;
	     $audit->add_audit_user("Login Failed", $login);
	
	     error('Wrong username or password', 'auth.wrong_user_or_pass');
	}
	```

	**Line ~151** - Change: 
			  
	```
	// Initialize session
	$this->authorized();

	// Check if saml
	```
	
	To: 
	
	```
	// Initialize session
	$this->authorized();

	// Load and audit the logout
	$audit = new Audit_mr_model;
	$audit->add_audit("Logout");

	// Check if saml
	```

* **`/munkireport-php/app/lib/munkireport/AuthHandler.php`**

	**Line ~4** - Change: 
	
	`use \Exception, \View, \Reportdata_model`
	
	To: 
	
	`use \Exception, \View, \Reportdata_model, \Audit_mr_model;`

	**Line ~38** - Change: 
			  
	```
	session_regenerate_id();

	return true;
	```
	
	To: 
	
	```
	session_regenerate_id();

	// Load and audit the login
	$audit = new Audit_mr_model;
	$audit->add_audit("Login");

	return true;
	```
	
	
	**Line ~62** - Change:
  
	```
	if ($authObj->getAuthStatus() == 'failed'){
		return false;
	}
	if ($authObj->getAuthStatus() == 'unauthorized'){

		error('Not authorized', 'auth.not_authorized');
	```
	
	
	To: 

	```
	if ($authObj->getAuthStatus() == 'failed'){

		// Load and audit the login
		$audit = new Audit_mr_model;
		$audit->add_audit_user("Login Failed", $login);

		return false;
	}
	if ($authObj->getAuthStatus() == 'unauthorized'){

		// Load and audit the login
		$audit = new Audit_mr_model;
		$audit->add_audit_user("Unauthorized", $login);

		error('Not authorized', 'auth.not_authorized');
	```

Table Schema
---
* username - (string) - Username of user doing action
* ip_address - (text) - IP address of action
* user_agent - (text) - User agent of user
* timestamp - (big int) - Timestamp of when action happened
* action - (string) - What action happened
* role - (string) - User's MunkiReport role
