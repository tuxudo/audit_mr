<?php

/**
 * Audit_mr module class
 *
 * @package munkireport
 * @author AvB
 **/
class Audit_mr_controller extends Module_controller
{
    public function __construct()
    {
        // Store module path
        $this->module_path = dirname(__FILE__);
    }

    public function index()
    {
        echo "You've loaded the audit_mr module!";
    }
    
    public function admin()
    {
        $obj = new View();
        $obj->view('audit_mr_admin', [], $this->module_path.'/views/');
    }


     /**
     * Retrieve adming data in json format for admin page
     *
     * @author tuxudo
     **/
    public function get_data_admin()
    {
        $obj = new View();

        // Check if the user is an admin user
        if ($_SESSION['role'] !== "admin" || ! $this->authorized()){
            $obj->view('json', array('msg' => 'Not authorized'));
            return;
        }
        
        $sql = "SELECT `username`, `timestamp`, `ip_address`, `action`, `role`, `user_agent`
                FROM audit_mr";
            
        $queryobj = new Audit_mr_model();
        jsonView($queryobj->query($sql));
    }
} // End class Audit_mr_controller
