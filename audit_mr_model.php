<?php

class Audit_mr_model extends \Model
{    
    public function __construct($serial = '')
    {
        parent::__construct('id', 'audit_mr'); // Primary key, tablename
        $this->rs['id'] = '';
        $this->rs['username'] = null;
        $this->rs['ip_address'] = null;
        $this->rs['user_agent'] = null;
        $this->rs['timestamp'] = time(); // Timestamp
        $this->rs['action'] = null;
        $this->rs['role'] = null;
    }

    // ------------------------------------------------------------------------

    /**
     * Add_audit method, called by AuthHandler to audit the logins
     *
     * @return void
     * @author tuxudo
     **/
    public function add_audit($action)
    {
        $this->rs['username'] = $_SESSION['user'];
        $this->rs['ip_address'] = getRemoteAddress();
        $this->rs['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $this->rs['action'] = $action;
        $this->rs['role'] = $_SESSION['role'];

        // Save audit data
        $this->save();
    }

    /**
     * Add_audit method, called by AuthHandler and Auth to audit the logins
     *
     * @return void
     * @author tuxudo
     **/
    public function add_audit_user($action, $username = "")
    {
        // Remove non-alphanumeric number characters
        $username = preg_replace("/[^A-Za-z0-9_\-]]/", '', $username);

        $this->rs['username'] = substr($username, 0, 254);
        $this->rs['ip_address'] = getRemoteAddress();
        $this->rs['user_agent'] = $_SERVER['HTTP_USER_AGENT'];
        $this->rs['action'] = $action;
        $this->rs['role'] = null;

        // Save audit data
        $this->save();
    }
}
