<?php defined('DIRECT') OR exit('No direct script access allowed');

class Model {

    public $db;

    function __construct()
    {
        if(ENVIRONMENT != 'production')
            require_once APP_DIR . 'config/' . ENVIRONMENT . '/db.php';
        else
            require_once APP_DIR . 'config/db.php';

        require_once SYSTEM_DIR . 'plugins/Database.php';

        $this->db = Database::init($config);
    }
    
}