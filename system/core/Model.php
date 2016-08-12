<?php defined('DIRECT') OR exit('No direct script access allowed');

class Model {

    public  $db;
    private $config;

    function __construct()
    {
        if(ENVIRONMENT != 'production')
            $this->config = require_once APP_DIR . 'config/' . ENVIRONMENT . '/db.php';
        else
            $this->config = require_once APP_DIR . 'config/db.php';

        require_once SYSTEM_DIR . 'plugins/Database.php';

        $this->db = Database::init($this->config);
    }
    
}